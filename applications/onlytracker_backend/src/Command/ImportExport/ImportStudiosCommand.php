<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Command\ImportExport;

use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportStudiosCommand extends Command
{
    private StudioRepositoryInterface $studioRepository;
    private string $path;

    public function __construct(StudioRepositoryInterface $studioRepository, string $path)
    {
        parent::__construct();

        $this->studioRepository = $studioRepository;
        $this->path = $path;
    }

    protected function configure()
    {
        $this
            ->setName('io:import:studios')
            ->setDescription('Create dump file with studios')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to serialized object');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path') ?: $this->path;
        $content = file_get_contents($path);
        $all = unserialize($content);
        $totalSaved = $totalUpdated = 0;
        /** @var \OnlyTracker\Domain\Entity\Studio $studio */
        foreach ($all as $studio) {
            $fromDatabase = $this->studioRepository->findBy(['url' => $studio->getUrl()]);
            if (empty($fromDatabase)) {
                $this->studioRepository->save($studio);
                $totalSaved++;
            } else {
                ($fromDatabase = array_shift($fromDatabase))->setStatus($studio->getStatus());
                $this->studioRepository->save($fromDatabase);
                $totalUpdated++;
            }
        }
        
        $output->writeln(sprintf('Total saved: %d.', $totalSaved));
        $output->writeln(sprintf('Total updated: %d.', $totalUpdated));

        return 0;
    }
}

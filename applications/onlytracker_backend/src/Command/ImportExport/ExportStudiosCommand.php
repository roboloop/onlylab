<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Command\ImportExport;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExportStudiosCommand extends Command
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
            ->setName('io:export:studios')
            ->setDescription('Create dump file with studios');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $preferable = $this->studioRepository->findBy(['status.value' => StudioStatus::PREFERABLE]);
        $banned = $this->studioRepository->findBy(['status.value' => StudioStatus::BANNED]);

        $all = [...$preferable, ...$banned];

        file_put_contents($this->path, serialize($all));

        $output->writeln('Total serialized: ' . count($all));
        
        return 0;
    }
}

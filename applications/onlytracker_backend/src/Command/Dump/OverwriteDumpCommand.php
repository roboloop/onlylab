<?php

namespace OnlyTracker\BackEnd\Command\Dump;

use OnlyTracker\Infrastructure\BackupManager\DatabaseManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OverwriteDumpCommand extends Command
{
    private $databaseBackup;
    private $container;

    public function __construct(ContainerInterface $container, DatabaseManager $databaseBackup)
    {
        parent::__construct();

        $this->databaseBackup = $databaseBackup;
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('db:dump:overwrite')
            ->setDescription('Create a dump of database and overwrite old versions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pattern = $this->container->getParameter('dump_path') . '/*';
        array_map('unlink', array_filter((array) glob($pattern)));

        $filename = (new \DateTime())->format('Y-m-d_H-i-s') . '.sql';
        $this->databaseBackup->backup($filename, 'gzip');

         $output->writeln([
            'Dump of database successfully created: ' . $filename,
         ]);
    }
}

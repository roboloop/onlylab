<?php

namespace App\Command;

use App\System\DatabaseManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDumpCommand extends Command
{
    private $databaseBackup;

    public function __construct(DatabaseManager $databaseBackup)
    {
        parent::__construct();

        $this->databaseBackup = $databaseBackup;
    }

    protected function configure()
    {
        $this
            ->setName('db:dump:create')
            ->setDescription('Create a dump of database.')
            ->addOption('null', null, InputOption::VALUE_NONE, 'Null driver');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $driver = $input->getOption('null') ? 'null' : 'gzip';

        $filename = (new \DateTime())->format('Y-m-d_H-i-s') . '.sql';
        $this->databaseBackup->backup($filename, $driver);

         $output->writeln([
            'Dump of database successfully created: ' . $filename,
        ]);
    }
}

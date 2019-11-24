<?php

namespace OnlyTracker\BackEnd\Command\Dump;

use OnlyTracker\Infrastructure\BackupManager\DatabaseManager;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDumpCommand extends Command
{
    private $dbManager;
    private $em;

    public function __construct(EntityManagerInterface $em, DatabaseManager $dbManager)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('db:dump:load')
            ->setDescription('Restore dump of database')
            ->addArgument('filename', InputArgument::REQUIRED, 'Sql-file')
            ->addOption('null', null, InputOption::VALUE_NONE, 'Null driver');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getConnection()->query('SET FOREIGN_KEY_CHECKS=0');

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $driver = $input->getOption('null') ? 'null' : 'gzip';

        $this->dbManager->restore($input->getArgument('filename'), $driver);

        $output->writeln([
            'Data is successfully restored',
        ]);
    }
}

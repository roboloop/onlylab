<?php

namespace OnlyTracker\BackEnd\Command;

use OnlyTracker\Infrastructure\BackupManager\DatabaseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LastDumpCommand extends Command
{
    private $dbManager;
    private $container;
    private $em;

    public function __construct(DatabaseManager $dbManager, EntityManagerInterface $em, ContainerInterface $container)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
        $this->container = $container;
        $this->em        = $em;
    }

    protected function configure()
    {
        $this
            ->setName('db:dump:last')
            ->setDescription('Restore last dump of database')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force')
            ->addOption('null', null, InputOption::VALUE_NONE, 'Null driver');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lastFile = $this->lastFile();
        if (is_null($lastFile)) {
            $output->writeln('<error>No file found</error>');
            return;
        }

        $this->em->getConnection()->query('SET FOREIGN_KEY_CHECKS=0');

        $driver = $input->getOption('null') ? 'null' : 'gzip';

        $this->dbManager->restore($lastFile, $driver);

        $output->writeln([
            'Data is successfully restored',
        ]);
    }

    private function lastFile(): ?string
    {
        $dir = $this->container->getParameter('dump_path');
        if (is_dir($dir) and $dh = opendir($dir)) {
            $files = [];
            while (($file = readdir($dh)) !== false) {
                $filename = $dir . '/' . $file;
                if (is_file($filename) and preg_match('/\.sql\.gz$/', $filename)) {
                    $files[] = $filename;
                }
            }
            closedir($dh);

            if (empty($files)) {
                return null;
            }

            return basename(max($files));
        }

        return null;
    }
}

<?php

namespace App\System;

use BackupManager\Filesystems\Destination;
use BackupManager\Manager;

class DatabaseManager
{
    /**
     * @var \BackupManager\Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function backup($fileName, $driver)
    {
        $destinations[] = new Destination('local', $fileName);

        $this->manager->makeBackup()->run('dev', $destinations, $driver);
    }

    public function restore($fileName, $driver)
    {
        $this->manager->makeRestore()->run('local', $fileName, 'dev', $driver);
    }
}

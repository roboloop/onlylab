<?php

namespace OnlyTracker\Infrastructure\BackupManager;

use BackupManager\Databases\MysqlDatabase as BaseMysqlDatabase;

class MysqlDatabase extends BaseMysqlDatabase
{
    public function getDumpCommandLine($outputPath)
    {
        $path = parent::getDumpCommandLine($outputPath);

        $path = preg_replace('/--routines/', '', $path);

        $path = preg_replace('/>/', '--no-create-info --skip-triggers --no-create-db --no-tablespaces --compact >', $path);

        return $path;
    }

    public function getRestoreCommandLine($inputPath)
    {
        // These settings are located here because the symfony bundle did not provide extraParams as a parameter in the config.
        $path = parent::getRestoreCommandLine($inputPath);

        $path = $path . ' --init-command "SET FOREIGN_KEY_CHECKS=0;"';

        return $path;
    }
}

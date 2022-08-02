<?php

declare(strict_types=1);

namespace space;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220802215901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE genres ADD status CLOB NOT NULL default 'unbanned'");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE genres DROP COLUMN status');
    }
}

<?php

declare(strict_types=1);

namespace space;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918201101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ex_users (id VARCHAR(255) NOT NULL, ex_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE forums (id VARCHAR(255) NOT NULL, title CLOB NOT NULL COLLATE NOCASE, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genres (id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL COLLATE NOCASE, description CLOB DEFAULT NULL, is_approved BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE images (id VARCHAR(255) NOT NULL, topic_id VARCHAR(255) NOT NULL, front_url CLOB NOT NULL, reference CLOB DEFAULT NULL, original CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_banner BOOLEAN NOT NULL, format VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E01FBE6A1F55203D ON images (topic_id)');
        $this->addSql('CREATE TABLE studios (id VARCHAR(255) NOT NULL, url CLOB NOT NULL COLLATE NOCASE, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , status CLOB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE topics (id VARCHAR(255) NOT NULL, forum_id VARCHAR(255) NOT NULL, size BIGINT DEFAULT NULL, ex_created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_loaded BOOLEAN NOT NULL, raw_title CLOB NOT NULL COLLATE NOCASE, title CLOB DEFAULT NULL COLLATE NOCASE, year VARCHAR(16) DEFAULT NULL COLLATE NOCASE, quality VARCHAR(16) DEFAULT NULL COLLATE NOCASE, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_91F6463929CCBAD0 ON topics (forum_id)');
        $this->addSql('CREATE TABLE genre_topic (topic_id VARCHAR(255) NOT NULL, genre_id VARCHAR(255) NOT NULL, PRIMARY KEY(topic_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_2046C1DE1F55203D ON genre_topic (topic_id)');
        $this->addSql('CREATE INDEX IDX_2046C1DE4296D31F ON genre_topic (genre_id)');
        $this->addSql('CREATE TABLE studio_topic (topic_id VARCHAR(255) NOT NULL, studio_id VARCHAR(255) NOT NULL, PRIMARY KEY(topic_id, studio_id))');
        $this->addSql('CREATE INDEX IDX_63F53ED61F55203D ON studio_topic (topic_id)');
        $this->addSql('CREATE INDEX IDX_63F53ED6446F285F ON studio_topic (studio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ex_users');
        $this->addSql('DROP TABLE forums');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE studios');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE genre_topic');
        $this->addSql('DROP TABLE studio_topic');
    }
}

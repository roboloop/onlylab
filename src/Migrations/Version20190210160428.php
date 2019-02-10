<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210160428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forums (id INT AUTO_INCREMENT NOT NULL, tracker_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FE5E5AB8628913D5 (tracker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, status INT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, type INT NOT NULL, preview VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, original VARCHAR(255) DEFAULT NULL, host INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E01FBE6A1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studios (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics (id INT AUTO_INCREMENT NOT NULL, forum_id INT DEFAULT NULL, title LONGTEXT NOT NULL, studio VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, quality VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, tracker_created_at DATETIME DEFAULT NULL, tracker_id INT NOT NULL, release_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_91F64639628913D5 (tracker_id), INDEX IDX_91F6463929CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_studio (topic_id INT NOT NULL, studio_id INT NOT NULL, INDEX IDX_E92C346E1F55203D (topic_id), INDEX IDX_E92C346E446F285F (studio_id), PRIMARY KEY(topic_id, studio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_genre (topic_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_38639F9A1F55203D (topic_id), INDEX IDX_38639F9A4296D31F (genre_id), PRIMARY KEY(topic_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE topics ADD CONSTRAINT FK_91F6463929CCBAD0 FOREIGN KEY (forum_id) REFERENCES forums (id)');
        $this->addSql('ALTER TABLE topic_studio ADD CONSTRAINT FK_E92C346E1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_studio ADD CONSTRAINT FK_E92C346E446F285F FOREIGN KEY (studio_id) REFERENCES studios (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_genre ADD CONSTRAINT FK_38639F9A1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_genre ADD CONSTRAINT FK_38639F9A4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE topics DROP FOREIGN KEY FK_91F6463929CCBAD0');
        $this->addSql('ALTER TABLE topic_genre DROP FOREIGN KEY FK_38639F9A4296D31F');
        $this->addSql('ALTER TABLE topic_studio DROP FOREIGN KEY FK_E92C346E446F285F');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A1F55203D');
        $this->addSql('ALTER TABLE topic_studio DROP FOREIGN KEY FK_E92C346E1F55203D');
        $this->addSql('ALTER TABLE topic_genre DROP FOREIGN KEY FK_38639F9A1F55203D');
        $this->addSql('DROP TABLE forums');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE studios');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE topic_studio');
        $this->addSql('DROP TABLE topic_genre');
    }
}

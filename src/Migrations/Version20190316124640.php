<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316124640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE studio_topic (topics_id INT NOT NULL, studios_id INT NOT NULL, INDEX IDX_63F53ED6BF06A414 (topics_id), INDEX IDX_63F53ED61CE19E68 (studios_id), PRIMARY KEY(topics_id, studios_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_topic (topics_id INT NOT NULL, genres_id INT NOT NULL, INDEX IDX_2046C1DEBF06A414 (topics_id), INDEX IDX_2046C1DE6A3B2603 (genres_id), PRIMARY KEY(topics_id, genres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tracker_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE studio_topic ADD CONSTRAINT FK_63F53ED6BF06A414 FOREIGN KEY (topics_id) REFERENCES topics (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE studio_topic ADD CONSTRAINT FK_63F53ED61CE19E68 FOREIGN KEY (studios_id) REFERENCES studios (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_topic ADD CONSTRAINT FK_2046C1DEBF06A414 FOREIGN KEY (topics_id) REFERENCES topics (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_topic ADD CONSTRAINT FK_2046C1DE6A3B2603 FOREIGN KEY (genres_id) REFERENCES genres (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE topic_genre');
        $this->addSql('DROP TABLE topic_studio');
        $this->addSql('ALTER TABLE topics DROP studio, DROP release_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE topic_genre (topic_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_38639F9A4296D31F (genre_id), INDEX IDX_38639F9A1F55203D (topic_id), PRIMARY KEY(topic_id, genre_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE topic_studio (topic_id INT NOT NULL, studio_id INT NOT NULL, INDEX IDX_E92C346E446F285F (studio_id), INDEX IDX_E92C346E1F55203D (topic_id), PRIMARY KEY(topic_id, studio_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE topic_genre ADD CONSTRAINT FK_38639F9A1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_genre ADD CONSTRAINT FK_38639F9A4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_studio ADD CONSTRAINT FK_E92C346E1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_studio ADD CONSTRAINT FK_E92C346E446F285F FOREIGN KEY (studio_id) REFERENCES studios (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE studio_topic');
        $this->addSql('DROP TABLE genre_topic');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE topics ADD studio VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD release_at DATETIME DEFAULT NULL');
    }
}

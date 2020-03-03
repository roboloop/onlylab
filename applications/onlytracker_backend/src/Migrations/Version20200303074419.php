<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303074419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ex_users (id VARCHAR(255) NOT NULL, ex_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forums (id INT NOT NULL, title VARCHAR(4095) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(4095) DEFAULT NULL, is_approved TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id VARCHAR(255) NOT NULL, topic_id INT NOT NULL, front_url VARCHAR(4095) NOT NULL, reference VARCHAR(4095) DEFAULT NULL, original VARCHAR(4095) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_banner TINYINT(1) NOT NULL, format VARCHAR(30) NOT NULL, INDEX IDX_E01FBE6A1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studios (id VARCHAR(255) NOT NULL, url VARCHAR(4095) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(4095) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics (id INT NOT NULL, forum_id INT NOT NULL, size INT DEFAULT NULL, ex_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_loaded TINYINT(1) NOT NULL, raw_title VARCHAR(4095) NOT NULL, title VARCHAR(4095) DEFAULT NULL, year VARCHAR(16) DEFAULT NULL, quality VARCHAR(16) DEFAULT NULL, INDEX IDX_91F6463929CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_topic (topic_id INT NOT NULL, genre_id VARCHAR(255) NOT NULL, INDEX IDX_2046C1DE1F55203D (topic_id), INDEX IDX_2046C1DE4296D31F (genre_id), PRIMARY KEY(topic_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studio_topic (topic_id INT NOT NULL, studio_id VARCHAR(255) NOT NULL, INDEX IDX_63F53ED61F55203D (topic_id), INDEX IDX_63F53ED6446F285F (studio_id), PRIMARY KEY(topic_id, studio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE topics ADD CONSTRAINT FK_91F6463929CCBAD0 FOREIGN KEY (forum_id) REFERENCES forums (id)');
        $this->addSql('ALTER TABLE genre_topic ADD CONSTRAINT FK_2046C1DE1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE genre_topic ADD CONSTRAINT FK_2046C1DE4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE studio_topic ADD CONSTRAINT FK_63F53ED61F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE studio_topic ADD CONSTRAINT FK_63F53ED6446F285F FOREIGN KEY (studio_id) REFERENCES studios (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE topics DROP FOREIGN KEY FK_91F6463929CCBAD0');
        $this->addSql('ALTER TABLE genre_topic DROP FOREIGN KEY FK_2046C1DE4296D31F');
        $this->addSql('ALTER TABLE studio_topic DROP FOREIGN KEY FK_63F53ED6446F285F');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A1F55203D');
        $this->addSql('ALTER TABLE genre_topic DROP FOREIGN KEY FK_2046C1DE1F55203D');
        $this->addSql('ALTER TABLE studio_topic DROP FOREIGN KEY FK_63F53ED61F55203D');
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

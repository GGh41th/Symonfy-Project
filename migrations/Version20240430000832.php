<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430000832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feed_back (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, submission_text LONGTEXT NOT NULL, date DATE NOT NULL, INDEX IDX_ED592A609D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATE NOT NULL, end_date DATE NOT NULL, completion_date DATE DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_527EDB259D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, birth_date DATE NOT NULL, username VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feed_back ADD CONSTRAINT FK_ED592A609D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB259D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feed_back DROP FOREIGN KEY FK_ED592A609D86650F');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB259D86650F');
        $this->addSql('DROP TABLE feed_back');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE `user`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209151923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE candidatures (id INT AUTO_INCREMENT NOT NULL, offres_id INT NOT NULL, clients_id INT NOT NULL, consulte TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_retenue TINYINT(1) DEFAULT NULL, INDEX IDX_DE57CF666C83CD9F (offres_id), INDEX IDX_DE57CF66AB014612 (clients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER  TABLE  candidatures ADD CONSTRAINT FK_DE57CF666C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id)');
        // $this->addSql('ALTER  TABLE candidatures ADD CONSTRAINT FK_DE57CF66AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF666C83CD9F');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66AB014612');
        $this->addSql('DROP TABLE candidatures');
    }
}

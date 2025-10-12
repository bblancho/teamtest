<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251012164658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients_skills (clients_id INT NOT NULL, skills_id INT NOT NULL, INDEX IDX_D0E6C297AB014612 (clients_id), INDEX IDX_D0E6C2977FF61858 (skills_id), PRIMARY KEY(clients_id, skills_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients_skills ADD CONSTRAINT FK_D0E6C297AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clients_skills ADD CONSTRAINT FK_D0E6C2977FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients_skills DROP FOREIGN KEY FK_D0E6C297AB014612');
        $this->addSql('ALTER TABLE clients_skills DROP FOREIGN KEY FK_D0E6C2977FF61858');
        $this->addSql('DROP TABLE clients_skills');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001123838 extends AbstractMigration
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
        $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA6C83CD9F');
        $this->addSql('ALTER TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D5311670727ACA70 FOREIGN KEY (parent_id) REFERENCES skills (id)');
        $this->addSql('CREATE INDEX IDX_D5311670727ACA70 ON skills (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients_skills DROP FOREIGN KEY FK_D0E6C297AB014612');
        $this->addSql('ALTER TABLE clients_skills DROP FOREIGN KEY FK_D0E6C2977FF61858');
        $this->addSql('DROP TABLE clients_skills');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D5311670727ACA70');
        $this->addSql('DROP INDEX IDX_D5311670727ACA70 ON skills');
        $this->addSql('ALTER TABLE skills DROP parent_id');
        $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA6C83CD9F');
        $this->addSql('ALTER TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

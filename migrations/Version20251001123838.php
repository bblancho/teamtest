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
        // $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA6C83CD9F');
        // $this->addSql('ALTER TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE skills ADD parent_id INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D5311670727ACA70 FOREIGN KEY (parent_id) REFERENCES skills (id)');
        // $this->addSql('CREATE INDEX IDX_D5311670727ACA70 ON skills (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D5311670727ACA70');
        $this->addSql('DROP INDEX IDX_D5311670727ACA70 ON skills');
        $this->addSql('ALTER TABLE skills DROP parent_id');
        $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA6C83CD9F');
        $this->addSql('ALTER TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

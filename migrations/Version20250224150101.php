<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224150101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offres_skills (offres_id INT NOT NULL, skills_id INT NOT NULL, INDEX IDX_2E00F5CA6C83CD9F (offres_id), INDEX IDX_2E00F5CA7FF61858 (skills_id), PRIMARY KEY(offres_id, skills_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, nom VARCHAR(50) NOT NULL, slug VARCHAR(60) NOT NULL, INDEX IDX_D531167067B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER  TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id)');
        $this->addSql('ALTER  TABLE offres_skills ADD CONSTRAINT FK_2E00F5CA7FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D531167067B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE candidatures CHANGE is_consulte consulte TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE clients ADD cv_name VARCHAR(255) DEFAULT NULL, ADD siren VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE societes ADD siret VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users DROP siret');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA6C83CD9F');
        $this->addSql('ALTER TABLE offres_skills DROP FOREIGN KEY FK_2E00F5CA7FF61858');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D531167067B3B43D');
        $this->addSql('DROP TABLE offres_skills');
        $this->addSql('DROP TABLE skills');
        $this->addSql('ALTER TABLE societes DROP siret');
        $this->addSql('ALTER TABLE users ADD siret VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE clients DROP cv_name, DROP siren');
        $this->addSql('ALTER TABLE candidatures CHANGE consulte is_consulte TINYINT(1) DEFAULT NULL');
    }
}

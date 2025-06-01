<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601050611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ADD cv_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE offres CHANGE profil profil LONGTEXT NOT NULL, CHANGE ref_mission ref_mission VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offres CHANGE profil profil LONGTEXT DEFAULT NULL, CHANGE ref_mission ref_mission VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE clients DROP cv_file');
    }
}

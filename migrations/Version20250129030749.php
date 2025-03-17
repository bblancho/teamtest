<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129030749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE  users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, cp INT NOT NULL, ville VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, type_user VARCHAR(50) NOT NULL, date_inscription_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', siret VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_newsletter TINYINT(1) DEFAULT NULL, last_longin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, tjm INT DEFAULT NULL, dispo TINYINT(1) DEFAULT NULL, date_dispo_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societes (id INT NOT NULL, nom_contact VARCHAR(255) DEFAULT NULL, num_contact VARCHAR(50) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, secteur_activite VARCHAR(255) DEFAULT NULL, phone_contact VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regions (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, societes_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(100) NOT NULL, tarif INT DEFAULT NULL, duree INT DEFAULT NULL, lieu_mission VARCHAR(100) NOT NULL, is_active TINYINT(1) NOT NULL, experience INT DEFAULT NULL, profil LONGTEXT DEFAULT NULL, contraintes LONGTEXT DEFAULT NULL, ref_mission VARCHAR(100) DEFAULT NULL, start_date_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_C6AC35447E841BEA (societes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE societes ADD CONSTRAINT FK_AEC76507BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC35447E841BEA FOREIGN KEY (societes_id) REFERENCES societes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74BF396750');
        $this->addSql('ALTER TABLE societes DROP FOREIGN KEY FK_AEC76507BF396750');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC35447E841BEA');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE societes');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

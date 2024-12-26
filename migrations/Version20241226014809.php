<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226014809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, tjm INT DEFAULT NULL, dispo TINYINT(1) DEFAULT NULL, date_dispo_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societes (id INT NOT NULL, nom_contact VARCHAR(255) DEFAULT NULL, num_contact VARCHAR(50) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, secteur_activite VARCHAR(255) DEFAULT NULL, phone_contact VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE societes ADD CONSTRAINT FK_AEC76507BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD nom VARCHAR(50) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD cp INT NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD phone VARCHAR(20) NOT NULL, ADD type_user VARCHAR(50) NOT NULL, ADD date_inscription_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD siret VARCHAR(255) NOT NULL, ADD is_verified TINYINT(1) NOT NULL, ADD is_newsletter TINYINT(1) DEFAULT NULL, ADD last_longin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74BF396750');
        $this->addSql('ALTER TABLE societes DROP FOREIGN KEY FK_AEC76507BF396750');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE societes');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE users DROP nom, DROP adresse, DROP cp, DROP ville, DROP phone, DROP type_user, DROP date_inscription_at, DROP siret, DROP is_verified, DROP is_newsletter, DROP last_longin_at, DROP discr');
    }
}

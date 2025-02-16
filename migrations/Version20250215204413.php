<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215204413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, duree VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, id_abonnement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, type_carte VARCHAR(255) NOT NULL, num_carte INT NOT NULL, date_expiration DATE NOT NULL, cvv INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, INDEX IDX_B1DC7A1E4FFF9576 (id_abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E4FFF9576 FOREIGN KEY (id_abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE cours ADD contenu_fichier VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP contenu');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E4FFF9576');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('ALTER TABLE cours ADD contenu LONGTEXT DEFAULT NULL, DROP contenu_fichier, DROP updated_at');
        $this->addSql('ALTER TABLE user DROP is_verified, CHANGE phonenumber phonenumber VARCHAR(255) NOT NULL');
    }
}

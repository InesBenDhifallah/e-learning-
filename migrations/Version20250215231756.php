<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215231756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, time VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, speaker VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_by VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, nbr_tickets INT NOT NULL, somme NUMERIC(10, 2) NOT NULL, payment_method VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_AB55E24F71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE abonnement ADD duree VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, DROP date_debut, DROP date_fin');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025F9295384');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B02520B9AB7E');
        $this->addSql('DROP INDEX IDX_8C62B02520B9AB7E ON chapitre');
        $this->addSql('DROP INDEX IDX_8C62B025F9295384 ON chapitre');
        $this->addSql('DROP INDEX IDX_8C62B0257ECF78B0 ON chapitre');
        $this->addSql('ALTER TABLE chapitre ADD module_id INT NOT NULL, DROP cours_id, DROP chapitres_id, DROP courses_id, CHANGE nomchapitre nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_8C62B025AFC2B591 ON chapitre (module_id)');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CAFC2B591');
        $this->addSql('DROP INDEX IDX_FDCA8C9CAFC2B591 ON cours');
        $this->addSql('ALTER TABLE cours ADD chapitre_id INT NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD contenu LONGTEXT DEFAULT NULL, DROP module_id, DROP nomcours, DROP matierecours, DROP niveaucours');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C1FBEEF7B ON cours (chapitre_id)');
        $this->addSql('ALTER TABLE module CHANGE nom_module nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD userid_id INT DEFAULT NULL, ADD date_paiement DATE NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E58E0A285 ON paiement (userid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE participation');
        $this->addSql('ALTER TABLE abonnement ADD date_debut DATE DEFAULT NULL, ADD date_fin DATE DEFAULT NULL, DROP duree, DROP description');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025AFC2B591');
        $this->addSql('DROP INDEX IDX_8C62B025AFC2B591 ON chapitre');
        $this->addSql('ALTER TABLE chapitre ADD cours_id INT DEFAULT NULL, ADD chapitres_id INT DEFAULT NULL, ADD courses_id INT DEFAULT NULL, DROP module_id, CHANGE nom nomchapitre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025F9295384 FOREIGN KEY (courses_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B02520B9AB7E FOREIGN KEY (chapitres_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_8C62B02520B9AB7E ON chapitre (chapitres_id)');
        $this->addSql('CREATE INDEX IDX_8C62B025F9295384 ON chapitre (courses_id)');
        $this->addSql('CREATE INDEX IDX_8C62B0257ECF78B0 ON chapitre (cours_id)');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C1FBEEF7B');
        $this->addSql('DROP INDEX IDX_FDCA8C9C1FBEEF7B ON cours');
        $this->addSql('ALTER TABLE cours ADD module_id INT DEFAULT NULL, ADD matierecours VARCHAR(255) NOT NULL, ADD niveaucours VARCHAR(255) NOT NULL, DROP chapitre_id, DROP contenu, CHANGE titre nomcours VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CAFC2B591 ON cours (module_id)');
        $this->addSql('ALTER TABLE module CHANGE nom nom_module VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E58E0A285');
        $this->addSql('DROP INDEX IDX_B1DC7A1E58E0A285 ON paiement');
        $this->addSql('ALTER TABLE paiement DROP userid_id, DROP date_paiement');
    }
}

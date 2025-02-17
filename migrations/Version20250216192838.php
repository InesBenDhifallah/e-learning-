<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216192838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD description VARCHAR(255) NOT NULL, CHANGE durée duree VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B02520B9AB7E');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025F9295384');
        $this->addSql('DROP INDEX IDX_8C62B0257ECF78B0 ON chapitre');
        $this->addSql('DROP INDEX IDX_8C62B02520B9AB7E ON chapitre');
        $this->addSql('DROP INDEX IDX_8C62B025F9295384 ON chapitre');
        $this->addSql('ALTER TABLE chapitre ADD module_id INT NOT NULL, DROP cours_id, DROP chapitres_id, DROP courses_id, CHANGE nomchapitre nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_8C62B025AFC2B591 ON chapitre (module_id)');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CAFC2B591');
        $this->addSql('DROP INDEX IDX_FDCA8C9CAFC2B591 ON cours');
        $this->addSql('ALTER TABLE cours ADD chapitre_id INT NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD contenu LONGTEXT DEFAULT NULL, DROP module_id, DROP nomcours, DROP matierecours, DROP niveaucours');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C1FBEEF7B ON cours (chapitre_id)');
        $this->addSql('ALTER TABLE event ADD titre VARCHAR(255) NOT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD localisation VARCHAR(255) DEFAULT NULL, ADD prix DOUBLE PRECISION NOT NULL, DROP name, DROP date, DROP time, DROP place, DROP category, DROP speaker, DROP created_by');
        $this->addSql('ALTER TABLE module CHANGE nom_module nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD userid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E58E0A285 ON paiement (userid_id)');
        $this->addSql('ALTER TABLE user ADD work VARCHAR(255) DEFAULT NULL, ADD adress VARCHAR(255) DEFAULT NULL, ADD pref VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE is_verified is_active TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX uniq_identifier_email ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD durée VARCHAR(255) NOT NULL, DROP duree, DROP description');
        $this->addSql('ALTER TABLE user DROP work, DROP adress, DROP pref, CHANGE email email VARCHAR(180) NOT NULL, CHANGE is_active is_verified TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025AFC2B591');
        $this->addSql('DROP INDEX IDX_8C62B025AFC2B591 ON chapitre');
        $this->addSql('ALTER TABLE chapitre ADD cours_id INT DEFAULT NULL, ADD chapitres_id INT DEFAULT NULL, ADD courses_id INT DEFAULT NULL, DROP module_id, CHANGE nom nomchapitre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B02520B9AB7E FOREIGN KEY (chapitres_id) REFERENCES cours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025F9295384 FOREIGN KEY (courses_id) REFERENCES cours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8C62B0257ECF78B0 ON chapitre (cours_id)');
        $this->addSql('CREATE INDEX IDX_8C62B02520B9AB7E ON chapitre (chapitres_id)');
        $this->addSql('CREATE INDEX IDX_8C62B025F9295384 ON chapitre (courses_id)');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C1FBEEF7B');
        $this->addSql('DROP INDEX IDX_FDCA8C9C1FBEEF7B ON cours');
        $this->addSql('ALTER TABLE cours ADD module_id INT DEFAULT NULL, ADD matierecours VARCHAR(255) NOT NULL, ADD niveaucours VARCHAR(255) NOT NULL, DROP chapitre_id, DROP contenu, CHANGE titre nomcours VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CAFC2B591 ON cours (module_id)');
        $this->addSql('ALTER TABLE event ADD name VARCHAR(255) NOT NULL, ADD date DATE NOT NULL, ADD time VARCHAR(255) NOT NULL, ADD place VARCHAR(255) NOT NULL, ADD category VARCHAR(255) NOT NULL, ADD speaker VARCHAR(255) NOT NULL, ADD created_by VARCHAR(255) NOT NULL, DROP titre, DROP date_debut, DROP date_fin, DROP type, DROP localisation, DROP prix');
        $this->addSql('ALTER TABLE module CHANGE nom nom_module VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E58E0A285');
        $this->addSql('DROP INDEX IDX_B1DC7A1E58E0A285 ON paiement');
        $this->addSql('ALTER TABLE paiement DROP userid_id');
    }
}

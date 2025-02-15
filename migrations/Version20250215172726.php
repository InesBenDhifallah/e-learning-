<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215172726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP durée');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('DROP INDEX IDX_8C62B0257ECF78B0 ON chapitre');
        $this->addSql('ALTER TABLE chapitre CHANGE cours_id module_id INT NOT NULL, CHANGE nomchapitre nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_8C62B025AFC2B591 ON chapitre (module_id)');
        $this->addSql('ALTER TABLE cours ADD chapitre_id INT NOT NULL, ADD contenu LONGTEXT DEFAULT NULL, CHANGE nomcours titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C1FBEEF7B ON cours (chapitre_id)');
        $this->addSql('ALTER TABLE module CHANGE nom_module nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD durée VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025AFC2B591');
        $this->addSql('DROP INDEX IDX_8C62B025AFC2B591 ON chapitre');
        $this->addSql('ALTER TABLE chapitre CHANGE module_id cours_id INT NOT NULL, CHANGE nom nomchapitre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_8C62B0257ECF78B0 ON chapitre (cours_id)');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C1FBEEF7B');
        $this->addSql('DROP INDEX IDX_FDCA8C9C1FBEEF7B ON cours');
        $this->addSql('ALTER TABLE cours DROP chapitre_id, DROP contenu, CHANGE titre nomcours VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE module CHANGE nom nom_module VARCHAR(255) NOT NULL');
    }
}

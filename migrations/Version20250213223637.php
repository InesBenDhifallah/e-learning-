<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213223637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, chapitres_id INT DEFAULT NULL, courses_id INT DEFAULT NULL, nomchapitre VARCHAR(255) NOT NULL, INDEX IDX_8C62B0257ECF78B0 (cours_id), INDEX IDX_8C62B02520B9AB7E (chapitres_id), INDEX IDX_8C62B025F9295384 (courses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, nomcours VARCHAR(255) NOT NULL, matierecours VARCHAR(255) NOT NULL, niveaucours VARCHAR(255) NOT NULL, INDEX IDX_FDCA8C9CAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, nom_module VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, id_abonnement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, type_carte VARCHAR(255) NOT NULL, num_carte INT NOT NULL, date_expiration DATE NOT NULL, cvv INT NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_B1DC7A1E4FFF9576 (id_abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, idq_id INT NOT NULL, question VARCHAR(255) NOT NULL, reponse VARCHAR(255) NOT NULL, solution VARCHAR(255) NOT NULL, INDEX IDX_B6F7494EB808F567 (idq_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, chapitre INT NOT NULL, bestg DOUBLE PRECISION NOT NULL, difficulte INT NOT NULL, etat TINYINT(1) NOT NULL, gain INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B02520B9AB7E FOREIGN KEY (chapitres_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025F9295384 FOREIGN KEY (courses_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E4FFF9576 FOREIGN KEY (id_abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EB808F567 FOREIGN KEY (idq_id) REFERENCES quizz (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B02520B9AB7E');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025F9295384');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CAFC2B591');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E4FFF9576');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EB808F567');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

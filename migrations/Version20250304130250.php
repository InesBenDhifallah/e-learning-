<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304130250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quizz_id INT NOT NULL, question VARCHAR(255) NOT NULL, INDEX IDX_B6F7494EBA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, chapitre INT NOT NULL, bestg VARCHAR(255) DEFAULT NULL, difficulte INT NOT NULL, best VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, gain VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suggestion (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, contenu VARCHAR(255) NOT NULL, est_correcte TINYINT(1) NOT NULL, INDEX IDX_DD80F31B1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paiement ADD stripe_session_id VARCHAR(255) DEFAULT NULL, DROP nom, DROP email, DROP type_carte, DROP num_carte, DROP date_expiration, DROP cvv');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBA934BCD');
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31B1E27F6BF');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE suggestion');
        $this->addSql('ALTER TABLE paiement ADD nom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD type_carte VARCHAR(255) NOT NULL, ADD num_carte VARCHAR(16) NOT NULL, ADD date_expiration DATE NOT NULL, ADD cvv INT NOT NULL, DROP stripe_session_id');
    }
}

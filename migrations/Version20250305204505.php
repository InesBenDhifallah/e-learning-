<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305204505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C7294869C (article_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz_result (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, quizz_id INT DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, quizztype VARCHAR(255) DEFAULT NULL, INDEX IDX_4995E702A76ED395 (user_id), INDEX IDX_4995E702BA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizz_result ADD CONSTRAINT FK_4995E702A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizz_result ADD CONSTRAINT FK_4995E702BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('ALTER TABLE cours ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD titre VARCHAR(255) NOT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD localisation VARCHAR(255) DEFAULT NULL, ADD prix DOUBLE PRECISION NOT NULL, DROP name, DROP date, DROP time, DROP place, DROP category, DROP speaker, DROP created_by');
        $this->addSql('ALTER TABLE paiement ADD stripe_session_id VARCHAR(255) DEFAULT NULL, DROP nom, DROP email, DROP type_carte, DROP num_carte, DROP date_expiration, DROP cvv');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E88B');
        $this->addSql('ALTER TABLE participation ADD created_at DATETIME NOT NULL, DROP somme, DROP payment_method, DROP status, CHANGE nbr_tickets user_id INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_AB55E24FA76ED395 ON participation (user_id)');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE quizz_result DROP FOREIGN KEY FK_4995E702A76ED395');
        $this->addSql('ALTER TABLE quizz_result DROP FOREIGN KEY FK_4995E702BA934BCD');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE quizz_result');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA76ED395');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E88B');
        $this->addSql('DROP INDEX IDX_AB55E24FA76ED395 ON participation');
        $this->addSql('ALTER TABLE participation ADD somme NUMERIC(10, 2) NOT NULL, ADD payment_method VARCHAR(20) NOT NULL, ADD status VARCHAR(20) NOT NULL, DROP created_at, CHANGE user_id nbr_tickets INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cours DROP description');
        $this->addSql('ALTER TABLE event ADD name VARCHAR(255) NOT NULL, ADD date DATE NOT NULL, ADD time VARCHAR(255) NOT NULL, ADD place VARCHAR(255) NOT NULL, ADD category VARCHAR(255) NOT NULL, ADD speaker VARCHAR(255) NOT NULL, ADD created_by VARCHAR(255) NOT NULL, DROP titre, DROP date_debut, DROP date_fin, DROP type, DROP localisation, DROP prix');
        $this->addSql('ALTER TABLE paiement ADD nom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD type_carte VARCHAR(255) NOT NULL, ADD num_carte INT NOT NULL, ADD date_expiration DATE NOT NULL, ADD cvv INT NOT NULL, DROP stripe_session_id');
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31B1E27F6BF');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305232250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quizz_result (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, quizz_id INT DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, quizztype VARCHAR(255) DEFAULT NULL, INDEX IDX_4995E702A76ED395 (user_id), INDEX IDX_4995E702BA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quizz_result ADD CONSTRAINT FK_4995E702A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizz_result ADD CONSTRAINT FK_4995E702BA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizz_result DROP FOREIGN KEY FK_4995E702A76ED395');
        $this->addSql('ALTER TABLE quizz_result DROP FOREIGN KEY FK_4995E702BA934BCD');
        $this->addSql('DROP TABLE quizz_result');
    }
}

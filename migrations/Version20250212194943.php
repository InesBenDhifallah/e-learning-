<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212194943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre ADD chapitres_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B02520B9AB7E FOREIGN KEY (chapitres_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_8C62B02520B9AB7E ON chapitre (chapitres_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B02520B9AB7E');
        $this->addSql('DROP INDEX IDX_8C62B02520B9AB7E ON chapitre');
        $this->addSql('ALTER TABLE chapitre DROP chapitres_id');
    }
}

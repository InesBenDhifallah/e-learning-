<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216195125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD idmatiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64939C5CF62 FOREIGN KEY (idmatiere_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64939C5CF62 ON user (idmatiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64939C5CF62');
        $this->addSql('DROP INDEX IDX_8D93D64939C5CF62 ON user');
        $this->addSql('ALTER TABLE user DROP idmatiere_id');
    }
}

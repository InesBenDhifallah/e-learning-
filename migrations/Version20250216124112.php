<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216124112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_user (module_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_37AF9345AFC2B591 (module_id), INDEX IDX_37AF9345A76ED395 (user_id), PRIMARY KEY(module_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_user DROP FOREIGN KEY FK_37AF9345AFC2B591');
        $this->addSql('ALTER TABLE module_user DROP FOREIGN KEY FK_37AF9345A76ED395');
        $this->addSql('DROP TABLE module_user');
    }
}

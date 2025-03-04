<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226121243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP nom, DROP email, DROP type_carte, DROP num_carte, DROP date_expiration, DROP cvv');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement ADD nom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD type_carte VARCHAR(255) NOT NULL, ADD num_carte VARCHAR(16) NOT NULL, ADD date_expiration DATE NOT NULL, ADD cvv INT NOT NULL');
    }
}

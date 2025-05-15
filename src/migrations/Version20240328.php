<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id to article table';
    }

    public function up(Schema $schema): void
    {
        // Ajouter la colonne user_id
        $this->addSql('ALTER TABLE article ADD user_id INT NOT NULL');
        
        // Ajouter la clé étrangère
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        
        // Créer l'index
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        
        // Mettre à jour les articles existants avec un ID utilisateur par défaut
        $this->addSql('UPDATE article SET user_id = (SELECT id FROM user LIMIT 1) WHERE user_id IS NULL');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la clé étrangère
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        
        // Supprimer l'index
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        
        // Supprimer la colonne
        $this->addSql('ALTER TABLE article DROP user_id');
    }
}
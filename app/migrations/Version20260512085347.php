<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512085347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, date_fin DATETIME NOT NULL, type VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, agent_id INT NOT NULL, INDEX IDX_2ED893483414710B (agent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED893483414710B FOREIGN KEY (agent_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED893483414710B');
        $this->addSql('DROP TABLE conge');
    }
}

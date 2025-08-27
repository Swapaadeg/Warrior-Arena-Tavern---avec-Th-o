<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250827085925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE queue_ticket (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, user_id INT NOT NULL, mmr INT NOT NULL, status VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AD41876A296CD8AE (team_id), INDEX IDX_AD41876AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watmatch (id INT AUTO_INCREMENT NOT NULL, team_a_id INT NOT NULL, team_b_id INT NOT NULL, winner_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_C2BC7D7EEA3FA723 (team_a_id), INDEX IDX_C2BC7D7EF88A08CD (team_b_id), INDEX IDX_C2BC7D7E5DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE queue_ticket ADD CONSTRAINT FK_AD41876A296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE queue_ticket ADD CONSTRAINT FK_AD41876AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE watmatch ADD CONSTRAINT FK_C2BC7D7EEA3FA723 FOREIGN KEY (team_a_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE watmatch ADD CONSTRAINT FK_C2BC7D7EF88A08CD FOREIGN KEY (team_b_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE watmatch ADD CONSTRAINT FK_C2BC7D7E5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES teams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE queue_ticket DROP FOREIGN KEY FK_AD41876A296CD8AE');
        $this->addSql('ALTER TABLE queue_ticket DROP FOREIGN KEY FK_AD41876AA76ED395');
        $this->addSql('ALTER TABLE watmatch DROP FOREIGN KEY FK_C2BC7D7EEA3FA723');
        $this->addSql('ALTER TABLE watmatch DROP FOREIGN KEY FK_C2BC7D7EF88A08CD');
        $this->addSql('ALTER TABLE watmatch DROP FOREIGN KEY FK_C2BC7D7E5DFCD4B8');
        $this->addSql('DROP TABLE queue_ticket');
        $this->addSql('DROP TABLE watmatch');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821112538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EC54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('CREATE INDEX IDX_3A29410EC54C8C93 ON characters (type_id)');
        $this->addSql('ALTER TABLE types DROP FOREIGN KEY FK_593089301221E019');
        $this->addSql('DROP INDEX IDX_593089301221E019 ON types');
        $this->addSql('ALTER TABLE types DROP perso_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EC54C8C93');
        $this->addSql('DROP INDEX IDX_3A29410EC54C8C93 ON characters');
        $this->addSql('ALTER TABLE characters DROP type_id');
        $this->addSql('ALTER TABLE types ADD perso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE types ADD CONSTRAINT FK_593089301221E019 FOREIGN KEY (perso_id) REFERENCES characters (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_593089301221E019 ON types (perso_id)');
    }
}

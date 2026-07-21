<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260720201413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY `FK_527EDB25A40BE5B6`');
        $this->addSql('DROP INDEX IDX_527EDB25A40BE5B6 ON task');
        $this->addSql('ALTER TABLE task ADD due_date DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, DROP priority, DROP assinged_to_id, CHANGE status status VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD priority VARCHAR(20) NOT NULL, ADD assinged_to_id INT DEFAULT NULL, DROP due_date, DROP created_at, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT `FK_527EDB25A40BE5B6` FOREIGN KEY (assinged_to_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A40BE5B6 ON task (assinged_to_id)');
    }
}

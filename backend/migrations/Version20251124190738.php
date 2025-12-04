<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124190738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parents_contact ADD parent1_first_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE parents_contact ADD parent1_last_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE parents_contact ADD parent1_phone VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE parents_contact ADD parent2_first_name VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE parents_contact DROP parent_name');
        $this->addSql('ALTER TABLE parents_contact DROP parent_email');
        $this->addSql('ALTER TABLE parents_contact RENAME COLUMN parent_phone TO parent2_phone');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parents_contact ADD parent_name VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE parents_contact ADD parent_email VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE parents_contact DROP parent1_first_name');
        $this->addSql('ALTER TABLE parents_contact DROP parent1_last_name');
        $this->addSql('ALTER TABLE parents_contact DROP parent1_phone');
        $this->addSql('ALTER TABLE parents_contact DROP parent2_first_name');
        $this->addSql('ALTER TABLE parents_contact RENAME COLUMN parent2_phone TO parent_phone');
    }
}

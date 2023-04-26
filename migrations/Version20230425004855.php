<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425004855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE console ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE console ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE serie ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE serie ADD image_size INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE console DROP image_name');
        $this->addSql('ALTER TABLE console DROP image_size');
        $this->addSql('ALTER TABLE serie DROP image_name');
        $this->addSql('ALTER TABLE serie DROP image_size');
    }
}

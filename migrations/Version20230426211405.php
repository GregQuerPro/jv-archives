<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426211405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE console ADD meta_title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE console ADD meta_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE serie ADD meta_title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE serie ADD meta_description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE console DROP meta_title');
        $this->addSql('ALTER TABLE console DROP meta_description');
        $this->addSql('ALTER TABLE serie DROP meta_title');
        $this->addSql('ALTER TABLE serie DROP meta_description');
    }
}

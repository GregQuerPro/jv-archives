<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510220637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD image_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE media ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE media ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN media.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN media.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" DROP image_name');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN image_size TO photo_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6497E9E4C8C FOREIGN KEY (photo_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497E9E4C8C ON "user" (photo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6497E9E4C8C');
        $this->addSql('DROP INDEX UNIQ_8D93D6497E9E4C8C');
        $this->addSql('ALTER TABLE "user" ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN photo_id TO image_size');
        $this->addSql('ALTER TABLE media DROP image_name');
        $this->addSql('ALTER TABLE media DROP image_size');
        $this->addSql('ALTER TABLE media DROP image_alt');
        $this->addSql('ALTER TABLE media DROP created_at');
        $this->addSql('ALTER TABLE media DROP updated_at');
    }
}

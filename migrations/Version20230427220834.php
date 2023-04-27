<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427220834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE commentaire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE commentaire (id INT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_67F068BCF675F31B ON commentaire (author_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC7294869C ON commentaire (article_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC727ACA70 ON commentaire (parent_id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC727ACA70 FOREIGN KEY (parent_id) REFERENCES commentaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE article ALTER title TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE article ALTER slug TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE article ALTER meta_title TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE article ALTER meta_description TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE console ALTER name TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE console ALTER meta_title TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE console ALTER meta_description TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE serie ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE serie ALTER slug TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE serie ALTER meta_title TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE serie ALTER meta_description TYPE VARCHAR(180)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE commentaire_id_seq CASCADE');
        $this->addSql('ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BCF675F31B');
        $this->addSql('ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BC7294869C');
        $this->addSql('ALTER TABLE commentaire DROP CONSTRAINT FK_67F068BC727ACA70');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('ALTER TABLE article ALTER title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE article ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE article ALTER meta_title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE article ALTER meta_description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE serie ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE serie ALTER slug TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE serie ALTER meta_title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE serie ALTER meta_description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE console ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE console ALTER meta_title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE console ALTER meta_description TYPE VARCHAR(255)');
    }
}

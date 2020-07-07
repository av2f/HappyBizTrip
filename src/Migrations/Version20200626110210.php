<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626110210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visit (visited_id INT NOT NULL, visitor_id INT NOT NULL, is_viewed TINYINT(1) NOT NULL, INDEX IDX_444839EAA76ED395 (visited_id), INDEX IDX_444839EA70BEE6D (visitor_id), PRIMARY KEY(visited_id, visitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_444839EAA76ED395 FOREIGN KEY (visited_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_444839EA70BEE6D FOREIGN KEY (visitor_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE visit');
    }
}

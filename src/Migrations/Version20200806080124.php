<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806080124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messaging (sender_id INT NOT NULL, receiver_id INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, readed_at DATETIME NOT NULL, is_readed TINYINT(1) NOT NULL, deleted_by_sender TINYINT(1) NOT NULL, deleted_by_receiver TINYINT(1) NOT NULL, INDEX IDX_EE15BA61F624B39D (sender_id), INDEX IDX_EE15BA61CD53EDB6 (receiver_id), PRIMARY KEY(sender_id, receiver_id, created_at)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messaging ADD CONSTRAINT FK_EE15BA61F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messaging ADD CONSTRAINT FK_EE15BA61CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messaging');
    }
}

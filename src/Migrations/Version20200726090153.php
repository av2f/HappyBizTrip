<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726090153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX search_idx ON user (pseudo, first_name, last_name)');
        $this->addSql('ALTER TABLE user_connect RENAME INDEX idx_74cff91fed442cf4 TO IDX_2CC2E715ED442CF4');
        $this->addSql('ALTER TABLE user_connect RENAME INDEX idx_74cff91f983624b7 TO IDX_2CC2E715983624B7');
        $this->addSql('ALTER TABLE visit RENAME INDEX idx_444839eaa76ed395 TO IDX_437EE93938C9FE8F');
        $this->addSql('ALTER TABLE visit RENAME INDEX idx_444839ea70bee6d TO IDX_437EE93970BEE6D');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX search_idx ON user');
        $this->addSql('ALTER TABLE user_connect RENAME INDEX idx_2cc2e715ed442cf4 TO IDX_74CFF91FED442CF4');
        $this->addSql('ALTER TABLE user_connect RENAME INDEX idx_2cc2e715983624b7 TO IDX_74CFF91F983624B7');
        $this->addSql('ALTER TABLE visit RENAME INDEX idx_437ee93938c9fe8f TO IDX_444839EAA76ED395');
        $this->addSql('ALTER TABLE visit RENAME INDEX idx_437ee93970bee6d TO IDX_444839EA70BEE6D');
    }
}

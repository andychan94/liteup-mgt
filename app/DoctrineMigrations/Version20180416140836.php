<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180416140836 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E692FC23A8 ON agency (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E6A0D96FBF ON agency (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E6C05FB297 ON agency (confirmation_token)');
        $this->addSql('ALTER TABLE house ADD deleted TINYINT(1) NOT NULL, CHANGE aircon aircon TINYINT(1) NOT NULL, CHANGE parking parking TINYINT(1) NOT NULL, CHANGE gas gas TINYINT(1) NOT NULL, CHANGE water water TINYINT(1) NOT NULL, CHANGE available available TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_70C0C6E692FC23A8 ON agency');
        $this->addSql('DROP INDEX UNIQ_70C0C6E6A0D96FBF ON agency');
        $this->addSql('DROP INDEX UNIQ_70C0C6E6C05FB297 ON agency');
        $this->addSql('ALTER TABLE house DROP deleted, CHANGE aircon aircon INT NOT NULL, CHANGE parking parking INT NOT NULL, CHANGE gas gas INT NOT NULL, CHANGE water water INT NOT NULL, CHANGE available available INT NOT NULL');
    }
}

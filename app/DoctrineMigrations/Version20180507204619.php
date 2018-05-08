<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180507204619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE house ADD balcony TINYINT(1) NOT NULL, ADD fence TINYINT(1) NOT NULL, ADD garage TINYINT(1) NOT NULL, ADD garden TINYINT(1) NOT NULL, ADD swpool TINYINT(1) NOT NULL, ADD fountain TINYINT(1) NOT NULL, DROP size, DROP commute, DROP essentials, DROP balcony_size, DROP gas, DROP water, DROP floor');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE house ADD size VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD commute VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD essentials VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD balcony_size VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD gas TINYINT(1) NOT NULL, ADD water TINYINT(1) NOT NULL, ADD floor VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP balcony, DROP fence, DROP garage, DROP garden, DROP swpool, DROP fountain');
    }
}

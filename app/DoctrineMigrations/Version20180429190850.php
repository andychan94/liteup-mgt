<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429190850 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_A393D2FB5E237E06 ON state (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E65E237E06 ON agency (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E6444F97DD ON agency (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70C0C6E6E48E9A13 ON agency (logo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D7943D685E237E06 ON area (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AE8547F85E237E06 ON lga (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14B78418B548B0F ON photo (path)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_70C0C6E65E237E06 ON agency');
        $this->addSql('DROP INDEX UNIQ_70C0C6E6444F97DD ON agency');
        $this->addSql('DROP INDEX UNIQ_70C0C6E6E48E9A13 ON agency');
        $this->addSql('DROP INDEX UNIQ_D7943D685E237E06 ON area');
        $this->addSql('DROP INDEX UNIQ_AE8547F85E237E06 ON lga');
        $this->addSql('DROP INDEX UNIQ_14B78418B548B0F ON photo');
        $this->addSql('DROP INDEX UNIQ_A393D2FB5E237E06 ON state');
    }
}

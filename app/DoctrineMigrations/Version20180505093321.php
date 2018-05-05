<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180505093321 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DBD0F409C');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DCDEADB2A');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DFF7C499B');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DBD0F409C FOREIGN KEY (area_id) REFERENCES area (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DCDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DFF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE lga DROP FOREIGN KEY FK_AE8547F85D83CC1');
        $this->addSql('ALTER TABLE lga ADD CONSTRAINT FK_AE8547F85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68FF7C499B');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68FF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784186BB74515');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784186BB74515 FOREIGN KEY (house_id) REFERENCES house (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68FF7C499B');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68FF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id)');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DCDEADB2A');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DBD0F409C');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DFF7C499B');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DCDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DFF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id)');
        $this->addSql('ALTER TABLE lga DROP FOREIGN KEY FK_AE8547F85D83CC1');
        $this->addSql('ALTER TABLE lga ADD CONSTRAINT FK_AE8547F85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784186BB74515');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784186BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
    }
}

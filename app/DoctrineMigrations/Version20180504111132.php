<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180504111132 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68FF7C499B');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68FF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id)');
        $this->addSql('ALTER TABLE lga DROP FOREIGN KEY FK_AE8547F85D83CC1');
        $this->addSql('ALTER TABLE lga ADD CONSTRAINT FK_AE8547F85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68FF7C499B');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68FF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE lga DROP FOREIGN KEY FK_AE8547F85D83CC1');
        $this->addSql('ALTER TABLE lga ADD CONSTRAINT FK_AE8547F85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id) ON DELETE SET NULL');
    }
}

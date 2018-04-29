<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429175601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, budget INT DEFAULT 0 NOT NULL, address VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, logo_size INT DEFAULT NULL, about LONGTEXT DEFAULT NULL, services LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_70C0C6E692FC23A8 (username_canonical), UNIQUE INDEX UNIQ_70C0C6E6A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_70C0C6E6C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, lga_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D7943D68FF7C499B (lga_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house (id INT AUTO_INCREMENT NOT NULL, agency_id INT DEFAULT NULL, area_id INT DEFAULT NULL, lga_id INT DEFAULT NULL, status INT NOT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, address VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, commute VARCHAR(255) DEFAULT NULL, essentials VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, balcony_size VARCHAR(255) DEFAULT NULL, aircon TINYINT(1) NOT NULL, parking TINYINT(1) NOT NULL, gas TINYINT(1) NOT NULL, water TINYINT(1) NOT NULL, floor VARCHAR(255) DEFAULT NULL, available TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, view_count INT NOT NULL, like_count INT NOT NULL, selling TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_67D5399DCDEADB2A (agency_id), INDEX IDX_67D5399DBD0F409C (area_id), INDEX IDX_67D5399DFF7C499B (lga_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lga (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AE8547F85D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, house_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_14B784186BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68FF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DCDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399DFF7C499B FOREIGN KEY (lga_id) REFERENCES lga (id)');
        $this->addSql('ALTER TABLE lga ADD CONSTRAINT FK_AE8547F85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784186BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DCDEADB2A');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DBD0F409C');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784186BB74515');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68FF7C499B');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399DFF7C499B');
        $this->addSql('ALTER TABLE lga DROP FOREIGN KEY FK_AE8547F85D83CC1');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE lga');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE state');
    }
}

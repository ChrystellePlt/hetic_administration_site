<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180317154032 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_accompanying_requests (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(70) NOT NULL, last_name VARCHAR(70) NOT NULL, email VARCHAR(255) NOT NULL, wanted_promotion VARCHAR(20) NOT NULL, actual_school_level VARCHAR(20) NOT NULL, twitter VARCHAR(30) NOT NULL, facebook VARCHAR(140) NOT NULL, phone_number VARCHAR(15) NOT NULL, wanted_speciality VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F499F243E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE app_accompanying_requests');
    }
}

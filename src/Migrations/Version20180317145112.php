<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180317145112 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users ADD first_name VARCHAR(70) NOT NULL, ADD last_name VARCHAR(70) NOT NULL, ADD promotion VARCHAR(20) NOT NULL, ADD twitter VARCHAR(30) NOT NULL, ADD facebook VARCHAR(140) NOT NULL, ADD phone_number VARCHAR(15) NOT NULL, ADD skills LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD personnal_email VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C250282444111930 ON app_users (personnal_email)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_C250282444111930 ON app_users');
        $this->addSql('ALTER TABLE app_users DROP first_name, DROP last_name, DROP promotion, DROP twitter, DROP facebook, DROP phone_number, DROP skills, DROP personnal_email');
    }
}

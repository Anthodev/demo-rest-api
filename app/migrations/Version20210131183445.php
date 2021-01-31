<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131183445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE poll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, poll_id INT NOT NULL, name VARCHAR(255) NOT NULL, votes INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A253C947C0F ON answer (poll_id)');
        $this->addSql('CREATE TABLE poll (id INT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, question VARCHAR(255) NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, do_users_must_be_connected BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, total_votes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84BCFA457E3C61F9 ON poll (owner_id)');
        $this->addSql('CREATE TABLE poll_user (poll_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(poll_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3AD5DD933C947C0F ON poll_user (poll_id)');
        $this->addSql('CREATE INDEX IDX_3AD5DD93A76ED395 ON poll_user (user_id)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(32) NOT NULL, code VARCHAR(16) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, role_id INT NOT NULL, username VARCHAR(32) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON "user" (role_id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A253C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA457E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_user ADD CONSTRAINT FK_3AD5DD933C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_user ADD CONSTRAINT FK_3AD5DD93A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A253C947C0F');
        $this->addSql('ALTER TABLE poll_user DROP CONSTRAINT FK_3AD5DD933C947C0F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE poll DROP CONSTRAINT FK_84BCFA457E3C61F9');
        $this->addSql('ALTER TABLE poll_user DROP CONSTRAINT FK_3AD5DD93A76ED395');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE poll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_user');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE "user"');
    }
}

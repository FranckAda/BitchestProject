<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218211559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acquiered_crypto (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, crypto_id_id INT NOT NULL, wallet_id_id INT NOT NULL, INDEX IDX_D08CA6E969F28E2C (crypto_id_id), INDEX IDX_D08CA6E9F43F82D (wallet_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE crypto_currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, actual_value DOUBLE PRECISION NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, crypto_id VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, wallet_id_id INT NOT NULL, INDEX IDX_EAA81A4CF43F82D (wallet_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, balance DOUBLE PRECISION NOT NULL, client_id_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_7C68921FDC2902E0 (client_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE acquiered_crypto ADD CONSTRAINT FK_D08CA6E969F28E2C FOREIGN KEY (crypto_id_id) REFERENCES crypto_currency (id)');
        $this->addSql('ALTER TABLE acquiered_crypto ADD CONSTRAINT FK_D08CA6E9F43F82D FOREIGN KEY (wallet_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CF43F82D FOREIGN KEY (wallet_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FDC2902E0 FOREIGN KEY (client_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acquiered_crypto DROP FOREIGN KEY FK_D08CA6E969F28E2C');
        $this->addSql('ALTER TABLE acquiered_crypto DROP FOREIGN KEY FK_D08CA6E9F43F82D');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CF43F82D');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921FDC2902E0');
        $this->addSql('DROP TABLE acquiered_crypto');
        $this->addSql('DROP TABLE crypto_currency');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

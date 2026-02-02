<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202132422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (euro_balance DOUBLE PRECISION NOT NULL, wallet_id INT NOT NULL, id INT NOT NULL, UNIQUE INDEX UNIQ_C7440455712520F3 (wallet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cotation (id INT AUTO_INCREMENT NOT NULL, value DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, crypto_currency_id INT NOT NULL, INDEX IDX_996DA94419932572 (crypto_currency_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE crypto_currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, symbol VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, wallet_id INT DEFAULT NULL, crypto_currency_id INT NOT NULL, INDEX IDX_723705D1712520F3 (wallet_id), INDEX IDX_723705D119932572 (crypto_currency_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(50) NOT NULL, role VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA94419932572 FOREIGN KEY (crypto_currency_id) REFERENCES crypto_currency (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119932572 FOREIGN KEY (crypto_currency_id) REFERENCES crypto_currency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455712520F3');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA94419932572');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1712520F3');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D119932572');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE cotation');
        $this->addSql('DROP TABLE crypto_currency');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

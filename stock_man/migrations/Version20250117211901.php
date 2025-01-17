<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117211901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE funcionario (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL, cargo INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lote (id INT AUTO_INCREMENT NOT NULL, funcionario_fk_id INT NOT NULL, produto_fk_id INT NOT NULL, preco_unitario NUMERIC(9, 2) NOT NULL, custo_unitario NUMERIC(9, 2) NOT NULL, data_entrada DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', validade DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', quantidade_inicial INT NOT NULL, INDEX IDX_65B4329FFA55414F (funcionario_fk_id), INDEX IDX_65B4329F178C928A (produto_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produto (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, categoria INT NOT NULL, quantidade INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saida (id INT AUTO_INCREMENT NOT NULL, transacao_fk_id INT NOT NULL, produto_fk_id INT NOT NULL, quantidade INT NOT NULL, INDEX IDX_87E8F2A681CD5916 (transacao_fk_id), INDEX IDX_87E8F2A6178C928A (produto_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transacao (id INT AUTO_INCREMENT NOT NULL, funcionario_fk_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6C9E60CEFA55414F (funcionario_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lote ADD CONSTRAINT FK_65B4329FFA55414F FOREIGN KEY (funcionario_fk_id) REFERENCES funcionario (id)');
        $this->addSql('ALTER TABLE lote ADD CONSTRAINT FK_65B4329F178C928A FOREIGN KEY (produto_fk_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE saida ADD CONSTRAINT FK_87E8F2A681CD5916 FOREIGN KEY (transacao_fk_id) REFERENCES transacao (id)');
        $this->addSql('ALTER TABLE saida ADD CONSTRAINT FK_87E8F2A6178C928A FOREIGN KEY (produto_fk_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEFA55414F FOREIGN KEY (funcionario_fk_id) REFERENCES funcionario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lote DROP FOREIGN KEY FK_65B4329FFA55414F');
        $this->addSql('ALTER TABLE lote DROP FOREIGN KEY FK_65B4329F178C928A');
        $this->addSql('ALTER TABLE saida DROP FOREIGN KEY FK_87E8F2A681CD5916');
        $this->addSql('ALTER TABLE saida DROP FOREIGN KEY FK_87E8F2A6178C928A');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CEFA55414F');
        $this->addSql('DROP TABLE funcionario');
        $this->addSql('DROP TABLE lote');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE saida');
        $this->addSql('DROP TABLE transacao');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240124002127 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency_pairs (id INT AUTO_INCREMENT NOT NULL, base_currency_code VARCHAR(3) NOT NULL, quote_currency_code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange_rates (id INT AUTO_INCREMENT NOT NULL, currency_pair_id INT NOT NULL, rate DOUBLE PRECISION NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_5AE3E774A311484C (currency_pair_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_rates ADD CONSTRAINT FK_5AE3E774A311484C FOREIGN KEY (currency_pair_id) REFERENCES currency_pairs (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE exchange_rates DROP FOREIGN KEY FK_5AE3E774A311484C');
        $this->addSql('DROP TABLE currency_pairs');
        $this->addSql('DROP TABLE exchange_rates');
    }
}

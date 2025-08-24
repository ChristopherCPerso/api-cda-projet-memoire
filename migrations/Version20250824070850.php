<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250824070850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_category (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_category_restaurants (payment_category_id INT NOT NULL, restaurants_id INT NOT NULL, INDEX IDX_1151F276E4CA4D38 (payment_category_id), INDEX IDX_1151F2764DCA160A (restaurants_id), PRIMARY KEY(payment_category_id, restaurants_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_category_restaurants ADD CONSTRAINT FK_1151F276E4CA4D38 FOREIGN KEY (payment_category_id) REFERENCES payment_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_category_restaurants ADD CONSTRAINT FK_1151F2764DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurants (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_category_restaurants DROP FOREIGN KEY FK_1151F276E4CA4D38');
        $this->addSql('ALTER TABLE payment_category_restaurants DROP FOREIGN KEY FK_1151F2764DCA160A');
        $this->addSql('DROP TABLE payment_category');
        $this->addSql('DROP TABLE payment_category_restaurants');
    }
}

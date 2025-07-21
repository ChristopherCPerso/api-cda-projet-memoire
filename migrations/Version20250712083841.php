<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250712083841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, domain VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_images (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, link VARCHAR(100) DEFAULT NULL, INDEX IDX_1181419BB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_schedule (id INT AUTO_INCREMENT NOT NULL, days_of_week VARCHAR(15) NOT NULL, open_time TIME NOT NULL, close_time TIME NOT NULL, is_closed TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurants_categories (restaurants_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_15F81A734DCA160A (restaurants_id), INDEX IDX_15F81A73A21214B7 (categories_id), PRIMARY KEY(restaurants_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurants_restaurant_schedule (restaurants_id INT NOT NULL, restaurant_schedule_id INT NOT NULL, INDEX IDX_A27C06284DCA160A (restaurants_id), INDEX IDX_A27C0628F570F8CF (restaurant_schedule_id), PRIMARY KEY(restaurants_id, restaurant_schedule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, author_id INT DEFAULT NULL, rating INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_794381C6B1E7706E (restaurant_id), INDEX IDX_794381C6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_images ADD CONSTRAINT FK_1181419BB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)');
        $this->addSql('ALTER TABLE restaurants_categories ADD CONSTRAINT FK_15F81A734DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurants_categories ADD CONSTRAINT FK_15F81A73A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurants_restaurant_schedule ADD CONSTRAINT FK_A27C06284DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurants_restaurant_schedule ADD CONSTRAINT FK_A27C0628F570F8CF FOREIGN KEY (restaurant_schedule_id) REFERENCES restaurant_schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('ALTER TABLE restaurant_images DROP FOREIGN KEY FK_1181419BB1E7706E');
        $this->addSql('ALTER TABLE restaurants_categories DROP FOREIGN KEY FK_15F81A734DCA160A');
        $this->addSql('ALTER TABLE restaurants_categories DROP FOREIGN KEY FK_15F81A73A21214B7');
        $this->addSql('ALTER TABLE restaurants_restaurant_schedule DROP FOREIGN KEY FK_A27C06284DCA160A');
        $this->addSql('ALTER TABLE restaurants_restaurant_schedule DROP FOREIGN KEY FK_A27C0628F570F8CF');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B1E7706E');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE restaurant_images');
        $this->addSql('DROP TABLE restaurant_schedule');
        $this->addSql('DROP TABLE restaurants_categories');
        $this->addSql('DROP TABLE restaurants_restaurant_schedule');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP INDEX IDX_1483A5E9979B1AD6 ON users');
        $this->addSql('ALTER TABLE users DROP company_id');
    }
}

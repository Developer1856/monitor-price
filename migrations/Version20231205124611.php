<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205124611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advert (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(255) NOT NULL, price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber_advert (subscriber_id INT NOT NULL, advert_id INT NOT NULL, INDEX IDX_6AA423527808B1AD (subscriber_id), INDEX IDX_6AA42352D07ECCB6 (advert_id), PRIMARY KEY(subscriber_id, advert_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscriber_advert ADD CONSTRAINT FK_6AA423527808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscriber_advert ADD CONSTRAINT FK_6AA42352D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriber_advert DROP FOREIGN KEY FK_6AA423527808B1AD');
        $this->addSql('ALTER TABLE subscriber_advert DROP FOREIGN KEY FK_6AA42352D07ECCB6');
        $this->addSql('DROP TABLE advert');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP TABLE subscriber_advert');
    }
}

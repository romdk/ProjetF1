<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215151235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circuit (id INT AUTO_INCREMENT NOT NULL, pays_id INT NOT NULL, id_api VARCHAR(50) NOT NULL, date_creation DATE NOT NULL, nb_virages VARCHAR(3) NOT NULL, longueur VARCHAR(10) NOT NULL, record_tour VARCHAR(20) NOT NULL, type_circuit VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, header VARCHAR(255) NOT NULL, layout VARCHAR(255) NOT NULL, map VARCHAR(255) NOT NULL, INDEX IDX_1325F3A6A6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecurie (id INT AUTO_INCREMENT NOT NULL, id_api VARCHAR(50) NOT NULL, date_creation DATE NOT NULL, localisation VARCHAR(50) NOT NULL, directeur VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, couleur VARCHAR(7) NOT NULL, nom_voiture VARCHAR(50) NOT NULL, puissance_voiture VARCHAR(4) NOT NULL, dimensions_voiture VARCHAR(20) NOT NULL, moteur_voiture VARCHAR(50) NOT NULL, image_voiture VARCHAR(255) NOT NULL, miniature_voiture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, grandprix_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prix INT NOT NULL, INDEX IDX_C0CF65F6765FCE6F (grandprix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grandprix (id INT AUTO_INCREMENT NOT NULL, id_api VARCHAR(50) NOT NULL, affiche VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, drapeau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pilote (id INT AUTO_INCREMENT NOT NULL, pays_id INT NOT NULL, id_api VARCHAR(50) NOT NULL, lieu_naissance VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, portrait1 VARCHAR(255) NOT NULL, portrait2 VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, img_casque VARCHAR(255) DEFAULT NULL, INDEX IDX_6A3254DDA6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, nb_personnes INT NOT NULL, INDEX IDX_42C84955C4598A51 (emplacement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE circuit ADD CONSTRAINT FK_1325F3A6A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F6765FCE6F FOREIGN KEY (grandprix_id) REFERENCES grandprix (id)');
        $this->addSql('ALTER TABLE pilote ADD CONSTRAINT FK_6A3254DDA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circuit DROP FOREIGN KEY FK_1325F3A6A6E44244');
        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F6765FCE6F');
        $this->addSql('ALTER TABLE pilote DROP FOREIGN KEY FK_6A3254DDA6E44244');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C4598A51');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE ecurie');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE grandprix');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE pilote');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200420145751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contenu_panier (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu_panier_produit (contenu_panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_179C43E361405BF (contenu_panier_id), INDEX IDX_179C43E3F347EFB (produit_id), PRIMARY KEY(contenu_panier_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu_panier_panier (contenu_panier_id INT NOT NULL, panier_id INT NOT NULL, INDEX IDX_7314121561405BF (contenu_panier_id), INDEX IDX_73141215F77D927C (panier_id), PRIMARY KEY(contenu_panier_id, panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenu_panier_produit ADD CONSTRAINT FK_179C43E361405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_panier_produit ADD CONSTRAINT FK_179C43E3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_panier_panier ADD CONSTRAINT FK_7314121561405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_panier_panier ADD CONSTRAINT FK_73141215F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contenu_panier_produit DROP FOREIGN KEY FK_179C43E361405BF');
        $this->addSql('ALTER TABLE contenu_panier_panier DROP FOREIGN KEY FK_7314121561405BF');
        $this->addSql('DROP TABLE contenu_panier');
        $this->addSql('DROP TABLE contenu_panier_produit');
        $this->addSql('DROP TABLE contenu_panier_panier');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}

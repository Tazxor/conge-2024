<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725083026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire CHANGE reponse_user reponse_user VARCHAR(255) DEFAULT NULL, CHANGE reponse_admin reponse_admin VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCCAAC9A59 FOREIGN KEY (conge_id) REFERENCES conges (id)');
        $this->addSql('ALTER TABLE label CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCCAAC9A59');
        $this->addSql('ALTER TABLE commentaire CHANGE reponse_user reponse_user VARCHAR(255) NOT NULL, CHANGE reponse_admin reponse_admin VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE label CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL');
    }
}

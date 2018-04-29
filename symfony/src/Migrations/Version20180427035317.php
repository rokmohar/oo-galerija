<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180427035317 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE gallery_image RENAME INDEX idx_c53d045ff675f31b TO IDX_21A0D47CF675F31B');
        $this->addSql('ALTER TABLE gallery_image RENAME INDEX idx_c53d045f4e7af8f TO IDX_21A0D47C4E7AF8F');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery DROP name');
        $this->addSql('ALTER TABLE gallery_image RENAME INDEX idx_21a0d47cf675f31b TO IDX_C53D045FF675F31B');
        $this->addSql('ALTER TABLE gallery_image RENAME INDEX idx_21a0d47c4e7af8f TO IDX_C53D045F4E7AF8F');
    }
}

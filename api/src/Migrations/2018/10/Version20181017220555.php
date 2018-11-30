<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181017220555 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('INSERT INTO statuses (id, name) VALUES (1, \'New\')');
        $this->addSql('INSERT INTO statuses (id, name) VALUES (2, \'In work\')');
        $this->addSql('INSERT INTO statuses (id, name) VALUES (3, \'Done\')');
        $this->addSql('INSERT INTO statuses (id, name) VALUES (4, \'Paid\')');
        $this->addSql('INSERT INTO statuses (id, name) VALUES (5, \'Cancelled\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM statuses WHERE "id" = 1');
        $this->addSql('DELETE FROM statuses WHERE "id" = 2');
        $this->addSql('DELETE FROM statuses WHERE "id" = 3');
        $this->addSql('DELETE FROM statuses WHERE "id" = 4');
        $this->addSql('DELETE FROM statuses WHERE "id" = 5');
    }
}

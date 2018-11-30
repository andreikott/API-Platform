<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181017220120 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE requests_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE clients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE companies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE projects_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statuses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE requests (id INT NOT NULL, product_id INT NOT NULL, client_id INT DEFAULT NULL, status_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description TEXT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B85D6514584665A ON requests (product_id)');
        $this->addSql('CREATE INDEX IDX_7B85D65119EB6921 ON requests (client_id)');
        $this->addSql('CREATE INDEX IDX_7B85D6516BF700BD ON requests (status_id)');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(35) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C82E74979B1AD6 ON clients (company_id)');
        $this->addSql('COMMENT ON COLUMN clients.phone IS \'(DC2Type:phone_number)\'');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, company_id INT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(500) NOT NULL, email VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
        $this->addSql('CREATE TABLE user_project (user_id INT NOT NULL, project_id INT NOT NULL, PRIMARY KEY(user_id, project_id))');
        $this->addSql('CREATE INDEX IDX_77BECEE4A76ED395 ON user_project (user_id)');
        $this->addSql('CREATE INDEX IDX_77BECEE4166D1F9C ON user_project (project_id)');
        $this->addSql('CREATE TABLE user_request (user_id INT NOT NULL, request_id INT NOT NULL, PRIMARY KEY(user_id, request_id))');
        $this->addSql('CREATE INDEX IDX_639A9195A76ED395 ON user_request (user_id)');
        $this->addSql('CREATE INDEX IDX_639A9195427EB8A5 ON user_request (request_id)');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE companies (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(35) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN companies.phone IS \'(DC2Type:phone_number)\'');
        $this->addSql('CREATE TABLE projects (id INT NOT NULL, product_id INT NOT NULL, client_id INT DEFAULT NULL, status_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description TEXT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C93B3A44584665A ON projects (product_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A419EB6921 ON projects (client_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A46BF700BD ON projects (status_id)');
        $this->addSql('CREATE TABLE statuses (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D6514584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D65119EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D6516BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_project ADD CONSTRAINT FK_77BECEE4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_project ADD CONSTRAINT FK_77BECEE4166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A9195A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A9195427EB8A5 FOREIGN KEY (request_id) REFERENCES requests (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A44584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A419EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A46BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_request DROP CONSTRAINT FK_639A9195427EB8A5');
        $this->addSql('ALTER TABLE requests DROP CONSTRAINT FK_7B85D65119EB6921');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A419EB6921');
        $this->addSql('ALTER TABLE user_project DROP CONSTRAINT FK_77BECEE4A76ED395');
        $this->addSql('ALTER TABLE user_request DROP CONSTRAINT FK_639A9195A76ED395');
        $this->addSql('ALTER TABLE requests DROP CONSTRAINT FK_7B85D6514584665A');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A44584665A');
        $this->addSql('ALTER TABLE clients DROP CONSTRAINT FK_C82E74979B1AD6');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9979B1AD6');
        $this->addSql('ALTER TABLE user_project DROP CONSTRAINT FK_77BECEE4166D1F9C');
        $this->addSql('ALTER TABLE requests DROP CONSTRAINT FK_7B85D6516BF700BD');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A46BF700BD');
        $this->addSql('DROP SEQUENCE requests_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE clients_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE companies_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE projects_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE statuses_id_seq CASCADE');
        $this->addSql('DROP TABLE requests');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_project');
        $this->addSql('DROP TABLE user_request');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE statuses');
    }
}

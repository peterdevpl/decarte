<?php declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190306154026 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE decarte_blog_posts (
				id INT AUTO_INCREMENT NOT NULL,
				name VARCHAR(255) NOT NULL,
				title VARCHAR(255) NOT NULL,
				contents LONGTEXT NOT NULL,
				created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
				updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
				PRIMARY KEY(id)
        	) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE decarte_blog_posts');
    }
}

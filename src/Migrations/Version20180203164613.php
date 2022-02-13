<?php

declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20180203164613 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
ALTER TABLE decarte_pages
ADD created_at DATETIME NOT NULL,
ADD updated_at DATETIME DEFAULT NULL');

        $this->addSql('
ALTER TABLE decarte_products
ADD created_at DATETIME NOT NULL AFTER sort,
ADD updated_at DATETIME DEFAULT NULL AFTER created_at,
DROP last_changed_at');

        $this->addSql('UPDATE decarte_pages SET created_at = NOW()');
        $this->addSql('UPDATE decarte_products SET created_at = NOW()');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE decarte_pages DROP created_at, DROP updated_at');

        $this->addSql('
ALTER TABLE decarte_products
DROP created_at,
DROP updated_at,
ADD last_changed_at INT UNSIGNED NOT NULL DEFAULT 0 AFTER sort');
    }
}

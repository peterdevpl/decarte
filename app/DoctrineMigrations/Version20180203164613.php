<?php

declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;

class Version20180203164613 extends AbstractMigration
{
    public function __construct(Version $version)
    {
        parent::__construct($version);
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );
    }

    public function up(Schema $schema)
    {
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

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE decarte_pages DROP created_at, DROP updated_at');

        $this->addSql('
ALTER TABLE decarte_products
DROP created_at,
DROP updated_at,
ADD last_changed_at INT UNSIGNED NOT NULL DEFAULT 0 AFTER sort');
    }
}

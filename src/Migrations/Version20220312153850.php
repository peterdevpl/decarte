<?php

declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220312153850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updated_at to product collections';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE decarte_product_collections ADD updated_at DATETIME DEFAULT NULL AFTER image_name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE decarte_product_collections DROP updated_at');
    }
}

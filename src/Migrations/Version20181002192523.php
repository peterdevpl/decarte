<?php declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181002192523 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decarte_orders MODIFY realization_type_id TINYINT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE decarte_product_collections ADD is_exclusive TINYINT(1) NOT NULL DEFAULT 0 AFTER product_type_id');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decarte_orders MODIFY realization_type_id TINYINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE decarte_product_collections DROP is_exclusive');
    }
}

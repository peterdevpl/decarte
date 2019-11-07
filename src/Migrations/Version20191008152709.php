<?php declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191008152709 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE `decarte_delivery_types` ' .
            'ADD `price_samples_home` SMALLINT UNSIGNED NOT NULL DEFAULT 0 AFTER `price`, ' .
            'ADD `price_samples_abroad` SMALLINT UNSIGNED NOT NULL DEFAULT 0 AFTER `price`, ' .
            'ADD `is_visible_for_samples` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `is_visible`');

        $this->addSql('ALTER TABLE `decarte_orders` ' .
            'ADD `type` ENUM(\'standard\', \'samples\') NOT NULL DEFAULT \'standard\', ' .
            'ADD `country` VARCHAR(255) DEFAULT NULL AFTER `city`');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE `decarte_orders` DROP `type`, DROP `country`');

        $this->addSql('ALTER TABLE `decarte_delivery_types` ' .
            'DROP `price_samples_home`, DROP `price_samples_abroad`, DROP `is_visible_for_samples`');
    }
}

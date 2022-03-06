<?php

declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180629175144 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE decarte_orders ' .
            'MODIFY street VARCHAR(255) DEFAULT NULL, ' .
            'MODIFY postal_code VARCHAR(255) DEFAULT NULL, ' .
            'MODIFY city VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE decarte_orders ' .
            'MODIFY street VARCHAR(255) NOT NULL, ' .
            'MODIFY postal_code VARCHAR(255) NOT NULL, ' .
            'MODIFY city VARCHAR(255) NOT NULL');
    }
}

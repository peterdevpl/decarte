<?php

declare(strict_types=1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181007194308 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE decarte_realization_types
            CHANGE days delivery_days TINYINT NOT NULL,
            ADD dtp_days TINYINT NOT NULL AFTER delivery_days');
        $this->addSql('UPDATE decarte_realization_types SET dtp_days = 5 WHERE id = 1');
        $this->addSql('UPDATE decarte_realization_types SET dtp_days = 3 WHERE id = 2');
        $this->addSql('UPDATE decarte_realization_types SET dtp_days = 1 WHERE id = 3');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE decarte_realization_types
            CHANGE delivery_days days INT NOT NULL,
            DROP dtp_days');
    }
}

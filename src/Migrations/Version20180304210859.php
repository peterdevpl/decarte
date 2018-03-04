<?php declare(strict_types = 1);

namespace Decarte\Shop\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;

class Version20180304210859 extends AbstractMigration
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
        $this->addSql('ALTER TABLE decarte_product_collections ADD title_seo VARCHAR(255) NOT NULL AFTER slug_name');
        $this->addSql('ALTER TABLE decarte_product_types
            ADD title_seo VARCHAR(255) NOT NULL AFTER slug_name,
            ADD description_seo VARCHAR(255) NOT NULL AFTER description');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE decarte_product_collections DROP title_seo');
        $this->addSql('ALTER TABLE decarte_product_types DROP title_seo, DROP description_seo');
    }
}

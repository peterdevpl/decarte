<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Entity\Product\ProductCollection;

final class ProductFixture
{
    public static function sampleInvitation(ProductCollection $productCollection): Product
    {
        return (new Product())
            ->setId(1)
            ->setName('MM01')
            ->setPrice(720)
            ->setDescription('Very nice model')
            ->setDescriptionSEO('You should check this out')
            ->setIsVisible(true)
            ->setHasDemo(true)
            ->setProductCollection($productCollection);
    }

    public static function sampleAddon(ProductCollection $productCollection): Product
    {
        return (new Product())
            ->setId(2)
            ->setName('MM01')
            ->setPrice(360)
            ->setDescription('Very nice label for the Mystic Moments collection')
            ->setDescriptionSEO('You should check this out, too')
            ->setIsVisible(true)
            ->setProductCollection($productCollection);
    }
}

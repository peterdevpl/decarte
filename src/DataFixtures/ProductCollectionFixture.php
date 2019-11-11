<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;

final class ProductCollectionFixture
{
    public static function sampleInvitations(ProductType $productType): ProductCollection
    {
        return (new ProductCollection())
            ->setName('Mystic Moments')
            ->setSlugName('mystic-moments')
            ->setTitleSEO('Mystic Moments are the perfect choice')
            ->setMinimumQuantity(20)
            ->setDescription('Example description of a products collection')
            ->setShortDescription('Very nice invitations')
            ->setProductType($productType)
            ->setIsVisible(true);
    }

    public static function sampleAddons(ProductType $productType): ProductCollection
    {
        return (new ProductCollection())
            ->setName('Labels')
            ->setSlugName('labels')
            ->setTitleSEO('Labels for guest with names')
            ->setMinimumQuantity(10)
            ->setDescription('Example description of a labels collection')
            ->setShortDescription('Very nice labels')
            ->setProductType($productType)
            ->setIsVisible(true);
    }
}

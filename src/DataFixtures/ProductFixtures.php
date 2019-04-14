<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Entity\Product\ProductCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var ProductCollection $mysticCollection */
        $mysticCollection = $this->getReference('mystic-moments');
        $invitation = new Product();
        $invitation
            ->setName('MM01')
            ->setPrice(720)
            ->setDescription('Very nice model')
            ->setDescriptionSEO('You should check this out')
            ->setIsVisible(true)
            ->setHasDemo(true)
            ->setProductCollection($mysticCollection);
        $manager->persist($invitation);

        /** @var ProductCollection $labels */
        $labels = $this->getReference('labels');
        $label = new Product();
        $label
            ->setName('MM01')
            ->setPrice(360)
            ->setDescription('Very nice label for the Mystic Moments collection')
            ->setDescriptionSEO('You should check this out, too')
            ->setIsVisible(true)
            ->setProductCollection($labels);
        $manager->persist($label);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductCollectionFixtures::class,
        ];
    }
}

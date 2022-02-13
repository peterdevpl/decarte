<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ProductCollectionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var ProductType $invitations */
        $invitations = $this->getReference('zaproszenia-slubne');
        $invitationsCollection = new ProductCollection();
        $invitationsCollection
            ->setName('Mystic Moments')
            ->setSlugName('mystic-moments')
            ->setTitleSEO('Mystic Moments are the perfect choice')
            ->setMinimumQuantity(20)
            ->setDescription('Example description of a products collection')
            ->setShortDescription('Very nice invitations')
            ->setProductType($invitations)
            ->setIsVisible(true);
        $this->addReference('mystic-moments', $invitationsCollection);
        $manager->persist($invitationsCollection);

        /** @var ProductType $addons */
        $addons = $this->getReference('dodatki');
        $addonsCollection = new ProductCollection();
        $addonsCollection
            ->setName('Labels')
            ->setSlugName('labels')
            ->setTitleSEO('Labels for guest with names')
            ->setMinimumQuantity(10)
            ->setDescription('Example description of a labels collection')
            ->setShortDescription('Very nice labels')
            ->setProductType($addons)
            ->setIsVisible(true);
        $this->addReference('labels', $addonsCollection);
        $manager->persist($addonsCollection);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductTypeFixtures::class,
        ];
    }
}

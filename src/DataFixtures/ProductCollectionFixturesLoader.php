<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class ProductCollectionFixturesLoader extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var ProductType $invitations */
        $invitations = $this->getReference('zaproszenia-slubne');
        $invitationsCollection = ProductCollectionFixture::sampleInvitations($invitations);
        $this->addReference('mystic-moments', $invitationsCollection);
        $manager->persist($invitationsCollection);

        /** @var ProductType $addons */
        $addons = $this->getReference('dodatki');
        $addonsCollection = ProductCollectionFixture::sampleAddons($addons);
        $this->addReference('labels', $addonsCollection);
        $manager->persist($addonsCollection);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductTypeFixturesLoader::class,
        ];
    }
}

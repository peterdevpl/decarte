<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\ProductCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class ProductFixturesLoader extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var ProductCollection $mysticCollection */
        $mysticCollection = $this->getReference('mystic-moments');
        $invitation = ProductFixture::sampleInvitation($mysticCollection);
        $manager->persist($invitation);

        /** @var ProductCollection $labels */
        $labels = $this->getReference('labels');
        $label = ProductFixture::sampleAddon($labels);
        $manager->persist($label);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductCollectionFixturesLoader::class,
        ];
    }
}

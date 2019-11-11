<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class DeliveryTypeFixturesLoader extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(DeliveryTypeFixture::bankTransfer());
        $manager->persist(DeliveryTypeFixture::cashOnDelivery());
        $manager->persist(DeliveryTypeFixture::personalCollection());
        $manager->persist(DeliveryTypeFixture::payU());

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\RealizationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class RealizationTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $standard = new RealizationType();
        $standard
            ->setId(1)
            ->setName('Standard')
            ->setDTPDays(7)
            ->setDeliveryDays(14)
            ->setPrice(0)
            ->setIsVisible(true);
        $manager->persist($standard);

        $express = new RealizationType();
        $express
            ->setId(2)
            ->setName('Express')
            ->setDTPDays(7)
            ->setDeliveryDays(7)
            ->setPrice(15000)
            ->setIsVisible(true);
        $manager->persist($express);

        $manager->flush();
    }
}

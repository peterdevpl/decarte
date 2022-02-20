<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\DeliveryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DeliveryTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $bankTransfer = new DeliveryType();
        $bankTransfer
            ->setId(1)
            ->setName('Delivery after a bank transfer')
            ->setShortName('bank transfer')
            ->setPrice(1400)
            ->setIsVisible(true);
        $manager->persist($bankTransfer);

        $cashOnDelivery = new DeliveryType();
        $cashOnDelivery
            ->setId(2)
            ->setName('Cash On Delivery')
            ->setShortName('COD')
            ->setPrice(1800);
        $manager->persist($cashOnDelivery);

        $personal = new DeliveryType();
        $personal
            ->setId(3)
            ->setName('Personal collection')
            ->setShortName('personal')
            ->setPrice(0)
            ->setIsPersonal(true)
            ->setIsVisible(true);
        $manager->persist($personal);

        $payU = new DeliveryType();
        $payU
            ->setId(4)
            ->setName('PayU transfer')
            ->setShortName('PayU')
            ->setPrice(1400)
            ->setIsVisible(true);
        $manager->persist($payU);

        $manager->flush();
    }
}

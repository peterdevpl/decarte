<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\DeliveryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class DeliveryTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $bankTransfer = new DeliveryType();
        $bankTransfer
            ->setName('Delivery after a bank transfer')
            ->setShortName('bank transfer')
            ->setPrice(1400);
        $manager->persist($bankTransfer);

        $cashOnDelivery = new DeliveryType();
        $cashOnDelivery
            ->setName('Cash On Delivery')
            ->setShortName('COD')
            ->setPrice(1800);
        $manager->persist($cashOnDelivery);

        $personal = new DeliveryType();
        $personal
            ->setName('Personal collection')
            ->setShortName('personal')
            ->setPrice(0)
            ->setIsPersonal(true);
        $manager->persist($personal);

        $payU = new DeliveryType();
        $payU
            ->setName('PayU transfer')
            ->setShortName('PayU')
            ->setPrice(1400);
        $manager->persist($payU);

        $manager->flush();
    }
}

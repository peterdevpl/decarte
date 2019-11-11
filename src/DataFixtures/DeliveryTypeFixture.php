<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\DeliveryType;

final class DeliveryTypeFixture
{
    public static function bankTransfer(): DeliveryType
    {
        return (new DeliveryType())
            ->setId(1)
            ->setName('Delivery after a bank transfer')
            ->setShortName('bank transfer')
            ->setPrice(1000);
    }

    public static function cashOnDelivery(): DeliveryType
    {
        return (new DeliveryType())
            ->setId(2)
            ->setName('Cash On Delivery')
            ->setShortName('COD')
            ->setPrice(1800);
    }

    public static function personalCollection(): DeliveryType
    {
        return (new DeliveryType())
            ->setId(3)
            ->setName('Personal collection')
            ->setShortName('personal')
            ->setPrice(0)
            ->setIsPersonal(true);
    }

    public static function payU(): DeliveryType
    {
        return (new DeliveryType())
            ->setId(4)
            ->setName('PayU transfer')
            ->setShortName('PayU')
            ->setPrice(1400);
    }
}

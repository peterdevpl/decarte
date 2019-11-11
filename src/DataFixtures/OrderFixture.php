<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Product\ProductType;

final class OrderFixture
{
    public static function exampleOrderWithTwoItems(): Order
    {
        $invitation = ProductFixture::sampleInvitation(ProductCollectionFixture::sampleInvitations(new ProductType()));
        $addon = ProductFixture::sampleAddon(ProductCollectionFixture::sampleAddons(new ProductType()));

        return (new Order())
            ->setRealizationType(RealizationTypeFixture::sampleRealizationType())
            ->setDeliveryType(DeliveryTypeFixture::bankTransfer())
            ->setCity('Gdańsk')
            ->setEmail('a@b.pl')
            ->setName('Jan Kowalski')
            ->setNotes('Xxx')
            ->setPhone('111222333')
            ->setPostalCode('80-534')
            ->setStreet('Starowiejska')
            ->addItem($invitation, 20, $invitation->getPrice())
            ->addItem($addon, 10, $addon->getPrice());
    }
}

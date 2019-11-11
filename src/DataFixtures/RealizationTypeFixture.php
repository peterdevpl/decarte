<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Order\RealizationType;

final class RealizationTypeFixture
{
    public static function sampleRealizationType(): RealizationType
    {
        return (new RealizationType())
            ->setId(2)
            ->setPrice(1500)
            ->setDTPDays(5);
    }
}

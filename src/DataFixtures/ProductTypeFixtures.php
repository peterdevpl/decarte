<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Product\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class ProductTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $invitations = new ProductType();
        $invitations
            ->setName('Zaproszenia ślubne')
            ->setSlugName('zaproszenia-slubne')
            ->setTitleSEO('Zaproszenia ślubne, zawiadomienia - proste, eleganckie, tradycyjne, nowoczesne')
            ->setDescription('Przykładowy opis zaproszeń ślubnych.')
            ->setDescriptionSEO('Przykładowy opis zaproszeń ślubnych na potrzeby SEO.')
            ->setSort(1)
            ->setIsVisible(true);
        $manager->persist($invitations);
        $this->addReference('zaproszenia-slubne', $invitations);

        $addons = new ProductType();
        $addons
            ->setName('Dodatki')
            ->setSlugName('dodatki')
            ->setTitleSEO('Dodatki')
            ->setDescription('Przykładowy opis dodatków ślubnych.')
            ->setDescriptionSEO('Przykładowy opis dodatków ślubnych na potrzeby SEO.')
            ->setSort(2)
            ->setIsVisible(true);
        $manager->persist($addons);
        $this->addReference('dodatki', $addons);

        $manager->flush();
    }
}

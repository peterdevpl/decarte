<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $page1 = new Page();
        $page1->setName('zamowienia');
        $page1->setTitle('Jak zamówić');
        $page1->setContents('Lorem ipsum');
        $manager->persist($page1);

        $page2 = new Page();
        $page2->setName('regulamin');
        $page2->setTitle('Regulamin');
        $page2->setContents('Lorem ipsum');
        $manager->persist($page2);

        $page3 = new Page();
        $page3->setName('o-nas');
        $page3->setTitle('O nas');
        $page3->setContents('Lorem ipsum');
        $manager->persist($page3);

        $page4 = new Page();
        $page4->setName('pytania-i-odpowiedzi');
        $page4->setTitle('Najczęściej zadawane pytania');
        $page4->setContents('Lorem ipsum');
        $manager->persist($page4);

        $page5 = new Page();
        $page5->setName('kontakt');
        $page5->setTitle('Kontakt');
        $page5->setContents('Lorem ipsum');
        $manager->persist($page5);

        $page6 = new Page();
        $page6->setName('polityka-prywatnosci');
        $page6->setTitle('Polityka prywatności RODO');
        $page6->setContents('Lorem ipsum');
        $manager->persist($page6);

        $manager->flush();
    }
}

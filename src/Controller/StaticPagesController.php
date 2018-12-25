<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Decarte\Shop\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StaticPagesController extends AbstractController
{
    /**
     * @Route("/informacje/{slugName}", name="static_page", requirements={"slugName": "[0-9a-z\-]+"})
     *
     * @param string $slugName
     */
    public function showAction(string $slugName, PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findByName($slugName);
        if (!$page) {
            throw $this->createNotFoundException('Nie znaleziono strony');
        }
        $page = $page[0];

        return $this->render('static/page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route(
     *     "/teksty/{section}",
     *     name="texts_section",
     *     requirements={"section": "cytaty|czcionki|grafiki|menu|teksty|wierszyki|zawieszki"}
     * )
     */
    public function textsSectionAction(string $section): Response
    {
        return $this->render('static/texts/' . $section . '.html.twig');
    }
}

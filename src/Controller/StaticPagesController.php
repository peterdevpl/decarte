<?php

namespace Decarte\Shop\Controller;

use Decarte\Shop\Repository\PageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StaticPagesController extends Controller
{
    /**
     * @Route("/informacje/{slugName}", name="static_page", requirements={"slugName": "[0-9a-z\-]+"})
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

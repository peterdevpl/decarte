<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaticPagesController extends Controller
{
    /**
     * @Route("/informacje/{slugName}", name="static_page", requirements={"slugName": "[0-9a-z\-]+"})
     * @param string $slugName
     */
    public function showAction($slugName)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->findByName($slugName);
        if (!$page) {
            throw $this->createNotFoundException('Nie znaleziono strony');
        }
        $page = $page[0];

        return $this->render('AppBundle:static:page.html.twig', [
            'page' => $page,
        ]);
    }
}

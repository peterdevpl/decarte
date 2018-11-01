<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     */
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }
}

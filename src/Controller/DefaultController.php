<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }
}

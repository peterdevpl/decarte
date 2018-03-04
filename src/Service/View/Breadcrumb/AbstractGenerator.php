<?php

namespace Decarte\Shop\Service\View\Breadcrumb;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractGenerator
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generateHomepageBreadcrumb(): Breadcrumb
    {
        $url = $this->router->generate('default', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new Breadcrumb(1, $url, 'Strona główna');
    }
}

<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Page;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PageUrl
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generate(Page $page, bool $absolute = false): string
    {
        return $this->router->generate('static_page', [
            'slugName' => $page->getName(),
        ], $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}

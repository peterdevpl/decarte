<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Product\ProductType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductTypeUrl
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generate(ProductType $type, bool $absolute = false): string
    {
        return $this->router->generate('shop_list_collections', [
            'type' => $type->getSlugName(),
        ], $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}

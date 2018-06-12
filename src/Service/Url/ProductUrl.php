<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Product\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductUrl
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generate(Product $product, bool $absolute = false): string
    {
        return $this->router->generate('shop_view_product', [
            'type' => $product->getProductCollection()->getProductType()->getSlugName(),
            'slugName' => $product->getProductCollection()->getSlugName(),
            'id' => $product->getId(),
        ], $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}

<?php

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Product\ProductCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductCollectionUrl
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generate(ProductCollection $collection, bool $absolute = false): string
    {
        return $this->router->generate('shop_view_collection', [
            'type' => $collection->getProductType()->getSlugName(),
            'slugName' => $collection->getSlugName(),
        ], $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}

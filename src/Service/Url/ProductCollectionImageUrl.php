<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Product\ProductCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ProductCollectionImageUrl
{
    private $imagineCacheManager;
    private $canonicalDomain;
    private $imagesDirectory;

    public function __construct(CacheManager $imagineCacheManager, string $canonicalDomain, string $imagesDirectory)
    {
        $this->imagineCacheManager = $imagineCacheManager;
        $this->canonicalDomain = $canonicalDomain;
        $this->imagesDirectory = $imagesDirectory;
    }

    public function generate(ProductCollection $collection): string
    {
        $absoluteLink = $this->imagineCacheManager->getBrowserPath(
            '/' . $this->imagesDirectory . '/' . $collection->getImageName(),
            'product_collection_thumb'
        );

        $urlParts = parse_url($absoluteLink);

        return $this->canonicalDomain . $urlParts['path'];
    }
}

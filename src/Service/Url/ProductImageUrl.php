<?php

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\Product\ProductImage;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ProductImageUrl
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

    public function getCanonicalUrl(ProductImage $image): string
    {
        $absoluteLink = $this->imagineCacheManager->getBrowserPath(
            '/' . $this->imagesDirectory . '/' . $image->getImageName(),
            'product_full'
        );

        $urlParts = parse_url($absoluteLink);

        return $this->canonicalDomain . $urlParts['path'];
    }
}

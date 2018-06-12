<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\View\Breadcrumb\Product;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Service\Url\ProductCollectionImageUrl;
use Decarte\Shop\Service\Url\ProductCollectionUrl;
use Decarte\Shop\Service\Url\ProductImageUrl;
use Decarte\Shop\Service\Url\ProductTypeUrl;
use Decarte\Shop\Service\Url\ProductUrl;
use Decarte\Shop\Service\View\Breadcrumb\AbstractGenerator;
use Decarte\Shop\Service\View\Breadcrumb\BreadcrumbList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductBreadcrumbs extends AbstractGenerator
{
    private $productTypeUrl;
    private $productCollectionUrl;
    private $productCollectionImageUrl;
    private $productUrl;
    private $productImageUrl;

    public function __construct(
        UrlGeneratorInterface $router,
        ProductTypeUrl $productTypeUrl,
        ProductCollectionUrl $productCollectionUrl,
        ProductCollectionImageUrl $productCollectionImageUrl,
        ProductUrl $productUrl,
        ProductImageUrl $productImageUrl
    ) {
        parent::__construct($router);
        $this->productTypeUrl = $productTypeUrl;
        $this->productCollectionUrl = $productCollectionUrl;
        $this->productCollectionImageUrl = $productCollectionImageUrl;
        $this->productUrl = $productUrl;
        $this->productImageUrl = $productImageUrl;
    }

    public function generate(Product $product): BreadcrumbList
    {
        $collection = $product->getProductCollection();
        $type = $collection->getProductType();

        $list = new BreadcrumbList();
        $list
            ->add($this->generateHomepageBreadcrumb())
            ->build(
                $this->productTypeUrl->generate($type, true),
                $type->getName()
            )
            ->build(
                $this->productCollectionUrl->generate($collection, true),
                $collection->getName(),
                $this->productCollectionImageUrl->generate($collection)
            )
            ->build(
                $this->productUrl->generate($product),
                'Model ' . $product->getName(),
                $this->productImageUrl->getCanonicalUrl($product->getCoverImage())
            );

        return $list;
    }
}

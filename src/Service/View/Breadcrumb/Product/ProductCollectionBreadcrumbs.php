<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\View\Breadcrumb\Product;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Service\Url\ProductCollectionImageUrl;
use Decarte\Shop\Service\Url\ProductCollectionUrl;
use Decarte\Shop\Service\Url\ProductTypeUrl;
use Decarte\Shop\Service\View\Breadcrumb\AbstractGenerator;
use Decarte\Shop\Service\View\Breadcrumb\BreadcrumbList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductCollectionBreadcrumbs extends AbstractGenerator
{
    private $productTypeUrl;
    private $productCollectionUrl;
    private $productCollectionImageUrl;

    public function __construct(
        UrlGeneratorInterface $router,
        ProductTypeUrl $productTypeUrl,
        ProductCollectionUrl $productCollectionUrl,
        ProductCollectionImageUrl $productCollectionImageUrl
    ) {
        parent::__construct($router);
        $this->productTypeUrl = $productTypeUrl;
        $this->productCollectionUrl = $productCollectionUrl;
        $this->productCollectionImageUrl = $productCollectionImageUrl;
    }

    public function generate(ProductCollection $collection): BreadcrumbList
    {
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
            );

        return $list;
    }
}

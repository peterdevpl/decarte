<?php

namespace Decarte\Shop\Service\View\Breadcrumb\Product;

use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Service\Url\ProductTypeUrl;
use Decarte\Shop\Service\View\Breadcrumb\AbstractGenerator;
use Decarte\Shop\Service\View\Breadcrumb\BreadcrumbList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductTypeBreadcrumbs extends AbstractGenerator
{
    private $productTypeUrl;

    public function __construct(
        UrlGeneratorInterface $router,
        ProductTypeUrl $productTypeUrl
    ) {
        parent::__construct($router);
        $this->productTypeUrl = $productTypeUrl;
    }

    public function generate(ProductType $type): BreadcrumbList
    {
        $list = new BreadcrumbList();
        $list
            ->add($this->generateHomepageBreadcrumb())
            ->build(
                $this->productTypeUrl->generate($type, true),
                $type->getName()
            );

        return $list;
    }
}

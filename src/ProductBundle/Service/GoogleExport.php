<?php

namespace ProductBundle\Service;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use ProductBundle\Entity\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GoogleExport
{
    private $router;
    private $imagineCacheManager;
    private $canonicalDomain;
    private $productImagesDirectory;
    private $merchantId;
    private $shoppingService;

    public function __construct(
        UrlGeneratorInterface $router,
        CacheManager $imagineCacheManager,
        string $canonicalDomain,
        string $productImagesDirectory,
        string $merchantId,
        string $googlePrivateKey
    ) {
        $client = new \Google_Client();
        $client->setApplicationName('decARTe');
        $client->setAuthConfig(json_decode($googlePrivateKey, true));
        $client->setScopes(\Google_Service_ShoppingContent::CONTENT);

        $this->router = $router;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->canonicalDomain = $canonicalDomain;
        $this->productImagesDirectory = $productImagesDirectory;
        $this->merchantId = $merchantId;
        $this->shoppingService = new \Google_Service_ShoppingContent($client);
    }

    public function exportProduct(Product $product)
    {
        $exportedProduct = $this->transformProduct($product);
        $response = $this->shoppingService->products->insert($this->merchantId, $exportedProduct);

        return $response;
    }

    protected function transformProduct(Product $product): \Google_Service_ShoppingContent_Product
    {
        $exportedProduct = new \Google_Service_ShoppingContent_Product();
        $exportedProduct->setOfferId($product->getId());
        $exportedProduct->setChannel('online');
        $exportedProduct->setContentLanguage('pl');
        $exportedProduct->setTargetCountry('PL');

        $exportedProduct->setTitle($product->getProductCollection()->getName() . ' - ' . $product->getName());
        $exportedProduct->setProductType(join(' > ', [
            $product->getProductCollection()->getProductType()->getName(),
            $product->getProductCollection()->getName(),
        ]));
        $exportedProduct->setPrice($this->getPrice($product));
        $exportedProduct->setAvailability('preorder');
        $exportedProduct->setDescription(strip_tags($product->getDescriptionSEO()));
        $exportedProduct->setBrand('decARTe');
        $exportedProduct->setCondition('new');

        $exportedProduct->setLink($this->canonicalDomain . $this->router->generate(
            'shop_view_product',
            [
                'type' => $product->getProductCollection()->getProductType()->getSlugName(),
                'slugName' => $product->getProductCollection()->getSlugName(),
                'id' => $product->getId(),
            ]
        ));

        $exportedProduct->setImageLink($this->getCanonicalImageUrl($product));

        return $exportedProduct;
    }

    protected function getPrice(Product $product): \Google_Service_ShoppingContent_Price
    {
        $money = Money::PLN($product->getPrice());
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return new \Google_Service_ShoppingContent_Price([
            'currency' => $money->getCurrency()->getCode(), 'value' => $formatter->format($money)
        ]);
    }

    protected function getCanonicalImageUrl(Product $product): string
    {
        $absoluteLink = $this->imagineCacheManager->getBrowserPath(
            '/' . $this->productImagesDirectory . '/' . $product->getCoverImage()->getImageName(),
            'product_full'
        );

        $urlParts = parse_url($absoluteLink);

        return $this->canonicalDomain . $urlParts['path'];
    }
}

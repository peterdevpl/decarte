<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Schema;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Service\Url\ProductImageUrl;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\OfferItemCondition;
use Spatie\SchemaOrg\Schema;

final class ProductSchema
{
    private $imageUrlGenerator;

    public function __construct(ProductImageUrl $imageUrlGenerator)
    {
        $this->imageUrlGenerator = $imageUrlGenerator;
    }

    public function generateProductData(Product $product): string
    {
        $money = new Money($product->getPrice(), new Currency('PLN'));
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        $availability = $product->isInStock() ? ItemAvailability::InStock : ItemAvailability::SoldOut;

        $schema = Schema::product()
            ->name($product->getProductCollection()->getName() . ' - ' . $product->getName())
            ->image($this->getImages($product))
            ->description($product->getDescriptionSEO())
            ->brand(Schema::brand()->name('decARTe'))
            ->offers(Schema::offer()
                ->priceCurrency($money->getCurrency()->getCode())
                ->price($formatter->format($money))
                ->itemCondition(OfferItemCondition::NewCondition)
                ->availability($availability));

        return $schema->toScript();
    }

    private function getImages(Product $product): array
    {
        $images = [];
        foreach ($product->getImages() as $image) {
            $images[] = $this->imageUrlGenerator->getCanonicalUrl($image);
        }

        return $images;
    }
}

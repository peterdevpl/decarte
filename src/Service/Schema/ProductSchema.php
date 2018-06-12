<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Schema;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Service\Url\ProductImageUrl;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Spatie\SchemaOrg\Schema;

class ProductSchema
{
    private $imageUrlGenerator;

    public function __construct(ProductImageUrl $imageUrlGenerator)
    {
        $this->imageUrlGenerator = $imageUrlGenerator;
    }

    public function generateProductData(Product $product): string
    {
        $money = Money::PLN($product->getPrice());
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        $schema = Schema::product()
            ->name($product->getProductCollection()->getName() . ' - ' . $product->getName())
            ->image($this->getImages($product))
            ->description($product->getDescriptionSEO())
            ->brand(Schema::brand()->name('decARTe'))
            ->offers(Schema::offer()
                ->priceCurrency($money->getCurrency()->getCode())
                ->price($formatter->format($money))
                ->itemCondition(Schema::offerItemCondition()->url('http://schema.org/NewCondition'))
                ->availability(Schema::itemAvailability()->url('http://schema.org/InStock')));

        return $schema->toScript();
    }

    protected function getImages(Product $product): array
    {
        $images = [];
        foreach ($product->getImages() as $image) {
            $images[] = $this->imageUrlGenerator->getCanonicalUrl($image);
        }

        return $images;
    }
}

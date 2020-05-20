<?php

declare(strict_types=1);

namespace Decarte\Shop\Exception;

use Decarte\Shop\Entity\Product\Product;

final class StockTooSmallException extends \InvalidArgumentException
{
    /**
     * @var Product
     */
    protected $product;
    protected $requestedQuantity;

    public function setContext(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->requestedQuantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    public function getStock(): int
    {
        return $this->product->getStock();
    }
}

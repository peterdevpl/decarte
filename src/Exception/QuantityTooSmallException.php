<?php

namespace Decarte\Shop\Exception;

use Decarte\Shop\Entity\Product\Product;

class QuantityTooSmallException extends \InvalidArgumentException
{
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
}

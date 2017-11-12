<?php

namespace OrderBundle\Exception;

use ProductBundle\Entity\Product;

class QuantityTooSmallException extends \InvalidArgumentException
{
    protected $product;
    protected $requestedQuantity;

    public function setContext(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->requestedQuantity = $quantity;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getRequestedQuantity()
    {
        return $this->requestedQuantity;
    }
}

<?php

declare(strict_types=1);

namespace Decarte\Shop\Exception;

use Decarte\Shop\Entity\Product\Product;

class QuantityTooSmallException extends \InvalidArgumentException
{
    protected $product;
    protected $requestedQuantity;

    public function __construct(Product $product, int $quantity)
    {
        parent::__construct();
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

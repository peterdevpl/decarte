<?php

namespace Decarte\Shop\Entity\Order\Samples;

use Decarte\Shop\Entity\Product\Product;

class OrderItem
{
    protected $product;

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }
}

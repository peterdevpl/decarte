<?php

namespace AppBundle\Entity;

use ProductBundle\Entity\Product;

class SamplesOrderItem
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

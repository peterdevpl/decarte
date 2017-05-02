<?php

namespace AppBundle\Entity;

class CartItem implements \JsonSerializable
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var int
     */
    protected $quantity = 0;

    /**
     * @var int
     */
    protected $unitPrice = 0;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->unitPrice = $product->getPrice();
        $this->setQuantity($quantity);
    }

    public function getId()
    {
        return $this->product->getId();
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException(sprintf('%d is wrong quantity', $quantity));
        }

        $this->quantity = $quantity;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $price)
    {
        if ($price < 0) {
            throw new \InvalidArgumentException(sprintf('%d is wrong unit price value', $price));
        }

        $this->unitPrice = $price;
        return $this;
    }

    public function getTotalPrice(): int
    {
        return $this->quantity * $this->unitPrice;
    }

    public function jsonSerialize()
    {
        return [
            'productId' => $this->product->getId(),
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
        ];
    }
}

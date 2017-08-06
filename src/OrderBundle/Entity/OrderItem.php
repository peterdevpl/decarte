<?php

namespace OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ProductBundle\Entity\Product;

/**
 * @ORM\Entity()
 * @ORM\Table(name="decarte_orders_items")
 */
class OrderItem implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * @var Order
     */
    private $order;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\ProductBundle\Entity\Product", inversedBy="orderItems")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @var Product
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $quantity = 1;

    /**
     * @ORM\Column(type="integer", name="unit_price")
     * @var int
     */
    private $unitPrice = 0;

    public function __construct(Order $order, Product $product)
    {
        $this->order = $order;
        $this->product = $product;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $price)
    {
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

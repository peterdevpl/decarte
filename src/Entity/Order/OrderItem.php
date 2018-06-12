<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Order;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Exception\QuantityTooSmallException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="decarte_orders_items")
 */
class OrderItem implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     *
     * @var Order
     */
    private $order;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Decarte\Shop\Entity\Product\Product", inversedBy="orderItems")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *
     * @var Product
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $quantity = 0;

    /**
     * @ORM\Column(type="integer", name="unit_price")
     *
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
        $minimumQuantity = $this->product->getMinimumQuantity();
        if ($quantity < $minimumQuantity) {
            $e = new QuantityTooSmallException();
            $e->setContext($this->product, $quantity);
            throw $e;
        }

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

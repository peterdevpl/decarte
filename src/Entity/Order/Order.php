<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Order;

use Decarte\Shop\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\OrderBundle\Repository\OrderRepository")
 * @ORM\Table(name="decarte_orders")
 */
class Order implements \JsonSerializable
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id = 0;

    /**
     * @ORM\Column(type="string")
     */
    private $email = '';

    /**
     * @ORM\Column(type="string")
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", name="postal_code", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     */
    private $phone = '';

    /**
     * @ORM\ManyToOne(targetEntity="RealizationType", inversedBy="orders")
     * @ORM\JoinColumn(name="realization_type_id", referencedColumnName="id")
     *
     * @var RealizationType
     */
    private $realizationType;

    /**
     * @ORM\ManyToOne(targetEntity="DeliveryType", inversedBy="orders")
     * @ORM\JoinColumn(name="delivery_type_id", referencedColumnName="id")
     *
     * @var DeliveryType
     */
    private $deliveryType;

    /** @ORM\Column(type="integer", name="total_price") */
    private $totalPrice = 0;

    /** @ORM\Column(type="string") */
    private $notes = '';

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRealizationType()
    {
        return $this->realizationType;
    }

    public function setRealizationType(RealizationType $type)
    {
        $this->realizationType = $type;
        $this->calculateTotalPrice();

        return $this;
    }

    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(DeliveryType $type)
    {
        $this->deliveryType = $type;
        $this->calculateTotalPrice();

        return $this;
    }

    public function getItemsPrice()
    {
        $sum = 0;
        foreach ($this->items as $item) {
            $sum += $item->getTotalPrice();
        }

        return $sum;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $price)
    {
        $this->totalPrice = $price;

        return $this;
    }

    protected function calculateTotalPrice()
    {
        $this->totalPrice = $this->getItemsPrice();

        if ($this->getDeliveryType()) {
            $this->totalPrice += $this->getDeliveryType()->getPrice();
        }

        if ($this->getRealizationType()) {
            $this->totalPrice += $this->getRealizationType()->getPrice();
        }
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = (string) $notes;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = mb_strtolower(trim($email), 'UTF-8');

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = trim($name);

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street)
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $code)
    {
        $this->postalCode = $code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city)
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function hasShippingAddress(): bool
    {
        return (null === $this->deliveryType) || !$this->deliveryType->isPersonal();
    }

    /**
     * @return ArrayCollection|OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(Product $product, int $quantity, int $unitPrice)
    {
        $item = $this->getItem($product);
        $item
            ->setQuantity($item->getQuantity() + $quantity)
            ->setUnitPrice($unitPrice);

        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }

        $this->calculateTotalPrice();

        return $this;
    }

    public function removeProduct(Product $product)
    {
        foreach ($this->items as $item) {
            if ($item->getProduct() === $product) {
                $this->items->removeElement($item);
            }
        }

        $this->calculateTotalPrice();

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return OrderItem
     */
    public function getItem(Product $product): OrderItem
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                return $item;
            }
        }

        return new OrderItem($this, $product);
    }

    /**
     * @param int $productId
     *
     * @return OrderItem
     */
    public function getItemById(int $productId): OrderItem
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $productId) {
                return $item;
            }
        }

        return new OrderItem($this, new Product());
    }

    public function clearItems()
    {
        $this->items->clear();
        $this->calculateTotalPrice();

        return $this;
    }

    public function getProductTypes(): array
    {
        $types = [];

        foreach ($this->getItems() as $item) {
            $type = $item->getProduct()->getProductCollection()->getProductType();
            if (!in_array($type, $types)) {
                $types[] = $type;
            }
        }

        return $types;
    }

    public function jsonSerialize()
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item;
        }

        return [
            'city' => $this->getCity(),
            'realizationTypeId' => $this->getRealizationType() ? $this->getRealizationType()->getId() : 0,
            'realizationPrice' => $this->getRealizationType() ? $this->getRealizationType()->getPrice() : 0,
            'deliveryTypeId' => $this->getDeliveryType() ? $this->getDeliveryType()->getId() : 0,
            'deliveryPrice' => $this->getDeliveryType() ? $this->getDeliveryType()->getPrice() : 0,
            'email' => $this->getEmail(),
            'id' => $this->getId(),
            'name' => $this->getName(),
            'notes' => $this->getNotes(),
            'phone' => $this->getPhone(),
            'postalCode' => $this->getPostalCode(),
            'price' => $this->getTotalPrice(),
            'street' => $this->getStreet(),
            'items' => $items,
        ];
    }
}

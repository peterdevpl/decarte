<?php

namespace OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ProductBundle\Entity\Product;

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
     * @ORM\Column(type="string")
     */
    private $street = '';

    /**
     * @ORM\Column(type="string", name="postal_code")
     */
    private $postalCode = '';

    /**
     * @ORM\Column(type="string")
     */
    private $city = '';

    /**
     * @ORM\Column(type="string")
     */
    private $phone = '';

    /**
     * @ORM\ManyToOne(targetEntity="DeliveryType", inversedBy="orders")
     * @ORM\JoinColumn(name="delivery_type_id", referencedColumnName="id")
     * @var DeliveryType
     */
    private $deliveryType;

    /** @ORM\Column(type="integer", name="total_price") */
    private $totalPrice = 0;

    /** @ORM\Column(type="string") */
    private $notes = '';

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
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
        $this->totalPrice =
            $this->getItemsPrice() +
            ($this->getDeliveryType() ? $this->getDeliveryType()->getPrice() : 0);
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

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street)
    {
        $this->street = trim($street);
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $code)
    {
        $this->postalCode = $code;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city)
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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(Product $product, int $quantity, int $unitPrice)
    {
        $item = new OrderItem($this, $product);
        $item->setQuantity($quantity)->setUnitPrice($unitPrice);
        $this->items->add($item);
        $this->calculateTotalPrice();
        return $this;
    }

    public function clearItems()
    {
        $this->items->clear();
        return $this;
    }

    public function jsonSerialize()
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item;
        }

        return [
            'city' => $this->getCity(),
            'deliveryTypeId' => $this->getDeliveryType() ? $this->getDeliveryType()->getId() : null,
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

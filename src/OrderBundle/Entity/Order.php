<?php

namespace OrderBundle\Entity;

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

    /** @ORM\Column(type="integer") */
    private $price = 0;

    /** @ORM\Column(type="string") */
    private $notes = '';

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
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
        return $this;
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

    public function jsonSerialize()
    {
        return [
            'city' => $this->getCity(),
            'deliveryTypeId' => $this->getDeliveryType()->getId(),
            'deliveryPrice' => $this->getDeliveryType()->getPrice(),
            'email' => $this->getEmail(),
            'id' => $this->getId(),
            'name' => $this->getName(),
            'notes' => $this->getNotes(),
            'phone' => $this->getPhone(),
            'postalCode' => $this->getPostalCode(),
            'price' => $this->getPrice(),
            'street' => $this->getStreet(),
        ];
    }
}

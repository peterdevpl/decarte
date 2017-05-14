<?php

namespace OrderBundle\Entity;

use CustomerBundle\Entity\Customer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\OrderBundle\Repository\OrderRepository")
 * @ORM\Table(name="decarte_orders")
 */
class Order
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected $email = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $street = '';

    /**
     * @ORM\Column(type="string", name="postal_code")
     */
    protected $postalCode = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $city = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $phone = '';

    /**
     * @ORM\ManyToOne(targetEntity="DeliveryType", inversedBy="orders")
     * @ORM\JoinColumn(name="delivery_type_id", referencedColumnName="id")
     */
    protected $deliveryType;

    /** @ORM\Column(type="integer") */
    protected $price = 0;

    /** @ORM\Column(type="string") */
    protected $notes = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
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
        return $this->name;
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
}

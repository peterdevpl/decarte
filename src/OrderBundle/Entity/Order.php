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

    protected $customer;

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
}

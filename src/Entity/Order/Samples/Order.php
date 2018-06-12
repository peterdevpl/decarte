<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Order\Samples;

use Doctrine\Common\Collections\ArrayCollection;

class Order
{
    protected $items;

    protected $notes = '';

    protected $email = '';

    protected $name = '';

    protected $address = '';

    protected $postalCode = '';

    protected $city = '';

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(OrderItem $item)
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(OrderItem $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes ?? '';

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;

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
}

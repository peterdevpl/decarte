<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Order\Samples;

use Decarte\Shop\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;

final class Order implements \JsonSerializable
{
    private $items;

    private $notes = '';

    private $email = '';

    private $name = '';

    private $address = '';

    private $postalCode = '';

    private $city = '';

    private $phone = '';

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    public function addItem(Product $item): self
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(Product $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes($notes): self
    {
        $this->notes = $notes ?? '';

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $code): self
    {
        $this->postalCode = $code;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function jsonSerialize()
    {
        $items = \array_map(function(Product $item) {
            return [
                'productId' => $item->getId(),
            ];
        }, $this->items->getValues());

        return [
            'city' => $this->city,
            'email' => $this->email,
            'name' => $this->name,
            'address' => $this->address,
            'postalCode' => $this->postalCode,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'items' => $items,
        ];
    }
}

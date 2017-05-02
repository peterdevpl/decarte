<?php

namespace AppBundle\Entity;

class Cart implements \JsonSerializable
{
    protected $id;

    /**
     * @var array
     */
    protected $items = [];

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param CartItem $item
     * @return $this
     */
    public function addItem(CartItem $item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param CartItem $item
     * @return $this
     */
    public function removeItem(CartItem $item)
    {
        $index = array_search($item, $this->items);
        if ($index !== false) {
            unset($this->items[$index]);
        }

        return $this;
    }

    /**
     * @param int $id
     * @return CartItem|null
     */
    public function getItem(int $id)
    {
        foreach ($this->items as $item) {
            if ($item->getId() == $id) {
                return $item;
            }
        }
    }

    public function getTotalPrice(): int
    {
        $sum = 0;
        foreach ($this->getItems() as $item) {
            $sum += $item->getTotalPrice();
        }

        return $sum;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id, 'items' => $this->items];
    }
}

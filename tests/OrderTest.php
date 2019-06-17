<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests;

use Decarte\Shop\Exception\QuantityTooSmallException;

final class OrderTest extends AbstractOrderTest
{
    public function testAddItems(): void
    {
        $this->assertEquals(2, count($this->order->getItems()));
        $this->assertEquals(1, count($this->order->getProductTypes()));
    }

    public function testAddExistingItem(): void
    {
        $this->order->addItem($this->products[0], 1, $this->products[0]->getPrice());

        $this->assertEquals(2, count($this->order->getItems()));
        $this->assertEquals(2, $this->order->getItem($this->products[0])->getQuantity());
        $this->assertEquals(2, $this->order->getItem($this->products[1])->getQuantity());
        $this->assertEquals(100, $this->order->getItemsPrice());
    }

    public function testRemoveProduct(): void
    {
        $this->order->removeProduct($this->products[1]);

        $this->assertEquals(1, count($this->order->getItems()));
        $this->assertEquals(1, $this->order->getItem($this->products[0])->getProduct()->getId());
        $this->assertEquals(20, $this->order->getItemsPrice());
        $this->assertEquals(45, $this->order->getTotalPrice());
    }

    public function testClearItems(): void
    {
        $this->order->clearItems();
        $this->assertEquals(0, count($this->order->getItems()));
        $this->assertEquals(0, $this->order->getItemsPrice());
        $this->assertEquals(25, $this->order->getTotalPrice());
    }

    public function testTotalPrice(): void
    {
        $this->assertEquals(80, $this->order->getItemsPrice());
        $this->assertEquals(105, $this->order->getTotalPrice());
    }

    public function testTooSmallQuantity(): void
    {
        $this->expectException(QuantityTooSmallException::class);
        $this->order->clearItems();
        $this->order->addItem($this->products[1], 1, 0);
    }

    public function testJson(): void
    {
        $json = json_encode($this->order);
        $expected = $this->getJsonEncodedOrder();
        $this->assertEquals($expected, $json);
    }
}

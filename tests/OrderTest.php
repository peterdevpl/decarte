<?php

namespace Tests;

class OrderTest extends AbstractOrderTest
{
    public function testAddItems()
    {
        $this->assertEquals(2, count($this->order->getItems()));
        $this->assertEquals(1, $this->order->getItem(1)->getProduct()->getId());
        $this->assertEquals(2, $this->order->getItem(2)->getProduct()->getId());
    }

    public function testRemoveProduct()
    {
        $lastItem = $this->order->getItem(2);
        $this->order->removeProduct($lastItem->getProduct());

        $this->assertEquals(1, count($this->order->getItems()));
        $this->assertEquals(1, $this->order->getItem(1)->getProduct()->getId());
    }

    public function testClearItems()
    {
        $this->order->clearItems();
        $this->assertEquals(0, count($this->order->getItems()));
    }

    public function testTotalPrice()
    {
        $this->assertEquals(80, $this->order->getItemsPrice());
        $this->assertEquals(90, $this->order->getTotalPrice());
    }

    public function testJson()
    {
        $json = json_encode($this->order);
        $expected = $this->getJsonEncodedOrder();
        $this->assertEquals($expected, $json);
    }
}

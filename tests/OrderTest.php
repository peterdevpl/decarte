<?php

namespace Tests;

class OrderTest extends AbstractOrderTest
{
    public function testAddItems()
    {
        $this->assertEquals(2, count($this->order->getItems()));
    }

    public function testAddExistingItem()
    {
        $this->order->addItem($this->products[0], 1, $this->products[0]->getPrice());

        $this->assertEquals(2, count($this->order->getItems()));
        $this->assertEquals(2, $this->order->getItem($this->products[0])->getQuantity());
        $this->assertEquals(2, $this->order->getItem($this->products[1])->getQuantity());
        $this->assertEquals(100, $this->order->getItemsPrice());
    }

    public function testRemoveProduct()
    {
        $this->order->removeProduct($this->products[1]);

        $this->assertEquals(1, count($this->order->getItems()));
        $this->assertEquals(1, $this->order->getItem($this->products[0])->getProduct()->getId());
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

    /**
     * @expectedException \OrderBundle\Exception\QuantityTooSmallException
     */
    public function testTooSmallQuantity()
    {
        $this->order->clearItems();
        $this->order->addItem($this->products[1], 1, 0);
    }

    public function testJson()
    {
        $json = json_encode($this->order);
        $expected = $this->getJsonEncodedOrder();
        $this->assertEquals($expected, $json);
    }
}

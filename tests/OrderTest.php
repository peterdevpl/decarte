<?php

use OrderBundle\Entity\DeliveryType;
use OrderBundle\Entity\Order;
use PHPUnit\Framework\TestCase;
use ProductBundle\Entity\Product;

class OrderTest extends TestCase
{
    /**
     * @var Order
     */
    protected $order;

    protected function setUp()
    {
        $product1 = new Product();
        $product1->setId(1)->setPrice(20);

        $product2 = new Product();
        $product2->setId(2)->setPrice(30);

        $deliveryType = new DeliveryType();
        $deliveryType->setPrice(10);

        $this->order = new Order();
        $this->order
            ->setDeliveryType($deliveryType)
            ->setCity('Gdańsk')
            ->setEmail('a@b.pl')
            ->setName('Jan Kowalski')
            ->setNotes('Xxx')
            ->setPhone('111222333')
            ->setPostalCode('80-534')
            ->setStreet('Starowiejska')
            ->addItem($product1, 1, $product1->getPrice())
            ->addItem($product2, 2, $product2->getPrice());
    }

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
        $expected = trim(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'order.json'));
        $this->assertEquals($expected, $json);
    }
}

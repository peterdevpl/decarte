<?php

use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    /**
     * @var CartItem
     */
    protected $item;

    public function setUp()
    {
        $product = new Product();
        $product
            ->setId(1)
            ->setPrice(20);

        $this->item = new CartItem($product, 10);
    }

    public function testSimpleProduct()
    {
        $this->assertEquals(1, $this->item->getProduct()->getId());
        $this->assertEquals(1, $this->item->getId());
        $this->assertEquals(10, $this->item->getQuantity());
    }

    public function testWrongQuantity()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->item->setQuantity(-1);
    }

    public function testTotalPrice()
    {
        $this->assertEquals(200, $this->item->getTotalPrice());
    }

    public function testUnitPrice()
    {
        $this->assertEquals(20, $this->item->getUnitPrice());

        $this->item->setUnitPrice(30);
        $this->assertEquals(30, $this->item->getUnitPrice());
        $this->assertEquals(300, $this->item->getTotalPrice());
    }

    public function testWrongUnitPrice()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->item->setUnitPrice(-1);
    }

    public function testJson()
    {
        $json = json_encode($this->item);
        $expected = '{"productId":1,"quantity":10,"unitPrice":20}';
        $this->assertEquals($expected, $json);
    }
}

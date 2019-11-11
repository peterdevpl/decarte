<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests\Entity\Order;

use Decarte\Shop\DataFixtures\OrderFixture;
use Decarte\Shop\DataFixtures\ProductCollectionFixture;
use Decarte\Shop\DataFixtures\ProductFixture;
use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Exception\QuantityTooSmallException;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testAddNewItems(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();

        // then
        $this->assertEquals(2, \count($order->getItems()));
        $this->assertEquals(1, \count($order->getProductTypes()));
        $this->assertEquals(18000, $order->getItemsPrice());
        $this->assertEquals(20500, $order->getTotalPrice());
    }

    public function testAddExistingItem(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();
        $product = $order->getItems()[0]->getProduct();

        // when
        $order->addItem($product, 1, $product->getPrice());

        // then
        $this->assertEquals(2, \count($order->getItems()));
        $this->assertEquals(21, $order->getItems()[0]->getQuantity());
        $this->assertEquals(10, $order->getItems()[1]->getQuantity());
        $this->assertEquals(18720, $order->getItemsPrice());
    }

    public function testRemoveProduct(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();
        $product = $order->getItems()[1]->getProduct();

        // when
        $order->removeProduct($product);

        // then
        $this->assertEquals(1, \count($order->getItems()));
        $this->assertEquals(1, $order->getItems()[0]->getProduct()->getId());
        $this->assertEquals(14400, $order->getItemsPrice());
    }

    public function testClearItems(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();

        // when
        $order->clearItems();

        // then
        $this->assertEquals(0, \count($order->getItems()));
        $this->assertEquals(0, $order->getItemsPrice());
        $this->assertEquals(2500, $order->getTotalPrice());
    }

    public function testTooSmallQuantity(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();
        $product = $order->getItems()[0]->getProduct();
        $order->clearItems();

        // expect
        $this->expectException(QuantityTooSmallException::class);

        // when
        $order->addItem($product, 1, 0);
    }

    public function testDomesticSamplesOrder(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();
        $order->clearItems();
        $order
            ->setType(Order::SAMPLES)
            ->addItem(
                ProductFixture::sampleInvitation(ProductCollectionFixture::sampleInvitations(new ProductType())),
                1,
                0
            )
            ->setCountry('PL');

        // then
        $this->assertEquals(1500, $order->getTotalPrice());
    }

    public function testForeignSamplesOrder(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();
        $order->clearItems();
        $order
            ->setType(Order::SAMPLES)
            ->addItem(
                ProductFixture::sampleInvitation(ProductCollectionFixture::sampleInvitations(new ProductType())),
                1,
                0
            )
            ->setCountry('GB');

        // then
        $this->assertEquals(2500, $order->getTotalPrice());
    }

    public function testJson(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();

        // when
        $json = \json_encode($order);

        // then
        $this->assertEquals($this->getJsonEncodedOrder(), $json);
    }

    private function getJsonEncodedOrder(): string
    {
        return \trim(\file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'order.json'));
    }
}

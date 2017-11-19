<?php

namespace Tests;

use OrderBundle\Entity\DeliveryType;
use OrderBundle\Entity\Order;
use OrderBundle\Entity\RealizationType;
use PHPUnit\Framework\TestCase;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductCollection;
use ProductBundle\Entity\ProductSeries;
use ProductBundle\Entity\ProductType;

abstract class AbstractOrderTest extends TestCase
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Product[]
     */
    protected $products;

    protected function setUp()
    {
        $this->products = [];

        $this->products[0] = new Product();
        $this->products[0]->setId(1)->setPrice(20)->setProductSeries($this->buildProductSeries(1));

        $this->products[1] = new Product();
        $this->products[1]->setId(2)->setPrice(30)->setProductSeries($this->buildProductSeries(2));

        $this->order = new Order();
        $this->order
            ->setRealizationType($this->getRealizationType())
            ->setDeliveryType($this->getDeliveryType())
            ->setCity('Gdańsk')
            ->setEmail('a@b.pl')
            ->setName('Jan Kowalski')
            ->setNotes('Xxx')
            ->setPhone('111222333')
            ->setPostalCode('80-534')
            ->setStreet('Starowiejska')
            ->addItem($this->products[0], 1, $this->products[0]->getPrice())
            ->addItem($this->products[1], 2, $this->products[1]->getPrice());
    }

    protected function getJsonEncodedOrder(): string
    {
        return trim(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'order.json'));
    }

    protected function getRealizationType(): RealizationType
    {
        $realizationType = new RealizationType();
        $realizationType
            ->setId(2)
            ->setPrice(15);

        return $realizationType;
    }

    protected function getDeliveryType(): DeliveryType
    {
        $deliveryType = new DeliveryType();
        $deliveryType
            ->setId(1)
            ->setPrice(10);

        return $deliveryType;
    }

    protected function buildProductSeries(int $minimumQuantity): ProductSeries
    {
        $productType = new ProductType();
        $productType->setMinimumQuantity($minimumQuantity);
        $productCollection = new ProductCollection();
        $productCollection->setProductType($productType);
        $productSeries = new ProductSeries();
        $productSeries->setProductCollection($productCollection);

        return $productSeries;
    }
}

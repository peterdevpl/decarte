<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests\Service;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Exception\StockTooSmallException;
use Decarte\Shop\Service\StockService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class StockServiceTest extends TestCase
{
    /** @var MockObject|ManagerRegistry */
    private $doctrine;

    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var StockService */
    private $stockService;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->getMockBuilder(ObjectManager::class)->getMock();

        $this->doctrine = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $this->doctrine->method('getManager')->willReturn($this->entityManager);

        $this->stockService = new StockService($this->doctrine);
    }

    /**
     * @test
     */
    public function itDecreasesStockForTheProductsWhichHaveDefinedStock(): void
    {
        // given
        $collection = new ProductCollection();
        $collection->setMinimumQuantity(1);
        $unlimitedProduct = new Product();
        $unlimitedProduct->setId(1);
        $unlimitedProduct->setProductCollection($collection);
        $limitedProduct = clone $unlimitedProduct;
        $limitedProduct->setId(2);
        $limitedProduct->setStock(1);
        $order = new Order();
        $order->addItem($unlimitedProduct, 1, 100);
        $order->addItem($limitedProduct, 1, 150);

        // expect
        $this->entityManager->expects($this->once())->method('persist');

        // when
        $this->stockService->checkAndUpdateProducts($order);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenOutOfStock(): void
    {
        // given
        $collection = new ProductCollection();
        $collection->setMinimumQuantity(1);
        $limitedProduct = new Product();
        $limitedProduct->setProductCollection($collection);
        $order = new Order();
        $order->addItem($limitedProduct, 1, 150);

        // expect
        $this->entityManager->expects($this->never())->method('persist');
        $this->expectException(StockTooSmallException::class);

        // when
        $limitedProduct->setStock(0);
        $this->stockService->checkAndUpdateProducts($order);
    }
}

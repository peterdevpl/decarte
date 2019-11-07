<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests;

use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\RealizationTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionOrderRepositoryTest extends AbstractOrderTest
{
    private $orderRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $session = new Session(new MockArraySessionStorage());
        $productRepository = $this->getProductRepository();
        $realizationTypeRepository = $this->getRealizationTypeRepository();
        $deliveryTypeRepository = $this->getDeliveryTypeRepository();

        $this->orderRepository = new SessionOrderRepository(
            $session,
            $productRepository,
            $realizationTypeRepository,
            $deliveryTypeRepository
        );
    }

    /**
     * @return ProductRepository&MockObject
     */
    private function getProductRepository(): ProductRepository
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('find')->will($this->returnValueMap([
            [1, null, null, $this->products[0]],
            [2, null, null, $this->products[1]],
        ]));

        return $productRepository;
    }

    private function getRealizationTypeRepository(): RealizationTypeRepository
    {
        $realizationTypeRepository = $this->createMock(RealizationTypeRepository::class);
        $realizationTypeRepository->method('find')->willReturn($this->getRealizationType());

        return $realizationTypeRepository;
    }

    private function getDeliveryTypeRepository(): DeliveryTypeRepository
    {
        $deliveryTypeRepository = $this->createMock(DeliveryTypeRepository::class);
        $deliveryTypeRepository->method('find')->willReturn($this->getDeliveryType());

        return $deliveryTypeRepository;
    }

    public function testGetStoredOrder(): void
    {
        $this->orderRepository->persist($this->order);
        $sessionOrder = $this->orderRepository->getOrder();
        $this->assertEquals($this->order, $sessionOrder);
    }
}

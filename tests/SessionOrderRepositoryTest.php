<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests;

use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\RealizationTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionOrderRepositoryTest extends AbstractOrderTest
{
    protected $orderRepository;

    protected function setUp()
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

    protected function getProductRepository()
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('find')->will($this->returnValueMap([
            [1, null, null, $this->products[0]],
            [2, null, null, $this->products[1]],
        ]));

        return $productRepository;
    }

    protected function getRealizationTypeRepository()
    {
        $realizationTypeRepository = $this->createMock(RealizationTypeRepository::class);
        $realizationTypeRepository->method('find')->willReturn($this->getRealizationType());

        return $realizationTypeRepository;
    }

    protected function getDeliveryTypeRepository()
    {
        $deliveryTypeRepository = $this->createMock(DeliveryTypeRepository::class);
        $deliveryTypeRepository->method('find')->willReturn($this->getDeliveryType());

        return $deliveryTypeRepository;
    }

    public function testGetStoredOrder()
    {
        $this->orderRepository->persist($this->order);
        $sessionOrder = $this->orderRepository->getOrder();
        $this->assertEquals($this->order, $sessionOrder);
    }
}

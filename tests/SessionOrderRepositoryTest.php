<?php

namespace Tests;

use OrderBundle\Repository\DeliveryTypeRepository;
use OrderBundle\Repository\SessionOrderRepository;
use ProductBundle\Repository\ProductRepository;
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
        $deliveryTypeRepository = $this->getDeliveryTypeRepository();

        $this->orderRepository = new SessionOrderRepository($session, $productRepository, $deliveryTypeRepository);
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

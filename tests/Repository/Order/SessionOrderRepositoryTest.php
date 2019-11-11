<?php

declare(strict_types=1);

namespace Decarte\Shop\Tests\Repository\Order;

use Decarte\Shop\DataFixtures\DeliveryTypeFixture;
use Decarte\Shop\DataFixtures\OrderFixture;
use Decarte\Shop\DataFixtures\ProductFixture;
use Decarte\Shop\DataFixtures\RealizationTypeFixture;
use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\RealizationTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionOrderRepositoryTest extends TestCase
{
    private $orderRepository;

    protected function setUp(): void
    {
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
            [1, null, null, ProductFixture::sampleInvitation(new ProductCollection())],
            [2, null, null, ProductFixture::sampleAddon(new ProductCollection())],
        ]));

        return $productRepository;
    }

    private function getRealizationTypeRepository(): RealizationTypeRepository
    {
        $realizationTypeRepository = $this->createMock(RealizationTypeRepository::class);
        $realizationTypeRepository->method('find')->willReturn(RealizationTypeFixture::sampleRealizationType());

        return $realizationTypeRepository;
    }

    private function getDeliveryTypeRepository(): DeliveryTypeRepository
    {
        $deliveryTypeRepository = $this->createMock(DeliveryTypeRepository::class);
        $deliveryTypeRepository->method('find')->willReturn(DeliveryTypeFixture::bankTransfer());

        return $deliveryTypeRepository;
    }

    public function testGetStoredOrder(): void
    {
        // given
        $order = OrderFixture::exampleOrderWithTwoItems();

        // when
        $this->orderRepository->persist($order);
        $sessionOrder = $this->orderRepository->getOrder();

        // then
        $this->assertEquals($order, $sessionOrder);
    }
}

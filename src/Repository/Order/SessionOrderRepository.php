<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository\Order;

use Decarte\Shop\Entity\Order\DeliveryType;
use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Order\RealizationType;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Repository\Product\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionOrderRepository
{
    const STANDARD = 'order';  // legacy key left as we don't want to break existing user sessions after deployment
    const SAMPLES = 'samples';

    /** @var SessionInterface */
    private $session;

    /** @var EntityRepository */
    private $productRepository;

    /** @var EntityRepository */
    private $realizationTypeRepository;

    /** @var EntityRepository */
    private $deliveryTypeRepository;

    /** @var Order[] */
    private $orders = [];

    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository,
        RealizationTypeRepository $realizationTypeRepository,
        DeliveryTypeRepository $deliveryTypeRepository
    ) {
        $this->realizationTypeRepository = $realizationTypeRepository;
        $this->deliveryTypeRepository = $deliveryTypeRepository;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function getOrder(string $key = self::STANDARD): Order
    {
        if (!\array_key_exists($key, $this->orders)) {
            $serializedOrder = $this->session->get($key);
            if ($serializedOrder) {
                $this->orders[$key] = $this->deserialize($serializedOrder);
            } else {
                $this->orders[$key] = new Order();
            }
        }

        return $this->orders[$key];
    }

    private function deserialize(string $serializedOrder): Order
    {
        $orderArray = \json_decode($serializedOrder, true);
        $order = new Order();

        if ($orderArray) {
            $order
                ->setCity($orderArray['city'])
                ->setEmail($orderArray['email'])
                ->setName($orderArray['name'])
                ->setStreet($orderArray['street'])
                ->setPostalCode($orderArray['postalCode'])
                ->setPhone($orderArray['phone'])
                ->setNotes($orderArray['notes'])
                ->setTotalPrice($orderArray['price']);

            foreach ($orderArray['items'] as $itemArray) {
                /** @var Product $product */
                $product = $this->productRepository->find($itemArray['productId']);
                $order->addItem($product, $itemArray['quantity'], $itemArray['unitPrice']);
            }

            if ($orderArray['realizationTypeId']) {
                /** @var RealizationType $realizationType */
                $realizationType = $this->realizationTypeRepository->find($orderArray['realizationTypeId']);
                $realizationType->setPrice($orderArray['realizationPrice']);
                $order->setRealizationType($realizationType);
            }

            if ($orderArray['deliveryTypeId']) {
                /** @var DeliveryType $deliveryType */
                $deliveryType = $this->deliveryTypeRepository->find($orderArray['deliveryTypeId']);
                $deliveryType->setPrice($orderArray['deliveryPrice']);
                $order->setDeliveryType($deliveryType);
            }
        }

        return $order;
    }

    public function persist(Order $order, string $key = self::STANDARD): void
    {
        $json = \json_encode($order);
        $this->session->set($key, $json);
        $this->orders[$key] = $order;
    }

    public function clear(string $key = self::STANDARD): void
    {
        $this->session->remove($key);
        unset($this->orders[$key]);
    }
}

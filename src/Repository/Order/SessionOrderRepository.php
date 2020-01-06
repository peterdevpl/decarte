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
    /** @var SessionInterface */
    private $session;

    /** @var EntityRepository */
    private $productRepository;

    /** @var EntityRepository */
    private $realizationTypeRepository;

    /** @var EntityRepository */
    private $deliveryTypeRepository;

    private $order;

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

    public function getOrder(): Order
    {
        if (!$this->order) {
            $serializedOrder = $this->session->get('order');
            if ($serializedOrder) {
                $this->order = $this->deserialize($serializedOrder);
            } else {
                $this->order = new Order();
            }
        }

        return $this->order;
    }

    private function deserialize(string $serializedOrder)
    {
        $orderArray = json_decode($serializedOrder, true);
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
                ->setTaxId($orderArray['taxId'])
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

    public function persist(Order $order): void
    {
        $this->session->set('order', json_encode($order));
    }

    public function clear(): void
    {
        $this->session->remove('order');
    }
}

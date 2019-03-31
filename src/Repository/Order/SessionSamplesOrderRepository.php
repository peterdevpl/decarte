<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository\Order;

use Decarte\Shop\Entity\Order\Samples\Order;
use Decarte\Shop\Repository\Product\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionSamplesOrderRepository
{
    private const SESSION_KEY = 'samplesOrder';

    /** @var SessionInterface */
    private $session;

    /** @var EntityRepository */
    private $productRepository;

    /** @var ?Order */
    private $order;

    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function getOrder(): Order
    {
        if (!$this->order) {
            $serializedOrder = $this->session->get(self::SESSION_KEY);
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
        $orderArray = \json_decode($serializedOrder, true);
        $order = new Order();

        if ($orderArray) {
            $order
                ->setCity($orderArray['city'])
                ->setEmail($orderArray['email'])
                ->setName($orderArray['name'])
                ->setAddress($orderArray['address'])
                ->setPostalCode($orderArray['postalCode'])
                ->setPhone($orderArray['phone'])
                ->setNotes($orderArray['notes']);

            foreach ($orderArray['items'] as $itemArray) {
                $product = $this->productRepository->find($itemArray['productId']);
                $order->addItem($product);
            }
        }

        return $order;
    }

    public function persist(Order $order): void
    {
        $this->session->set(self::SESSION_KEY, \json_encode($order));
    }

    public function clear(): void
    {
        $this->session->remove(self::SESSION_KEY);
    }
}

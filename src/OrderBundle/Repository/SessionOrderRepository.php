<?php

namespace OrderBundle\Repository;

use OrderBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionOrderRepository
{
    /** @var SessionInterface */
    private $session;

    private $order;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getOrder()
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

    protected function deserialize(string $serializedOrder)
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
                ->setNotes($orderArray['notes']);
        }

        return $order;
    }

    public function persist(Order $order)
    {
        $this->session->set('order', json_encode($order));
    }
}

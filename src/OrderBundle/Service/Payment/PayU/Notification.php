<?php

namespace OrderBundle\Service\Payment\PayU;

class Notification
{
    private $result;

    public function __construct(\OpenPayU_Result $result)
    {
        $this->result = $result;
    }

    public function getOrderId(): string
    {
        return $this->result->getResponse()->order->orderId;
    }

    public function getStatus(): string
    {
        return $this->result->getResponse()->order->status;
    }

    public function isCompleted(): bool
    {
        return $this->getStatus() === 'COMPLETED';
    }
}

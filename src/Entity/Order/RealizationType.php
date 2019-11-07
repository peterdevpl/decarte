<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Order;

use Decarte\Shop\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Decarte\Shop\Repository\Order\RealizationTypeRepository")
 * @ORM\Table(name="decarte_realization_types")
 */
class RealizationType
{
    use VisibilityTrait;

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id = 0;

    /** @ORM\Column(type="string") */
    protected $name = '';

    /** @ORM\Column(type="integer") */
    private $deliveryDays = 0;

    /** @ORM\Column(type="integer") */
    private $dtpDays = 0;

    /** @ORM\Column(type="integer") */
    protected $price = 0;

    /** @ORM\Column(type="string", name="shop_email_suffix") */
    protected $shopEmailSuffix = '';

    /** @ORM\Column(type="string", name="customer_email_prefix") */
    protected $customerEmailPrefix = '';

    /** @ORM\Column(type="integer") */
    protected $sort = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDeliveryDays(): int
    {
        return $this->deliveryDays;
    }

    public function setDeliveryDays(int $deliveryDays): self
    {
        $this->deliveryDays = $deliveryDays;

        return $this;
    }

    public function getDTPDays(): int
    {
        return $this->dtpDays;
    }

    public function setDTPDays(int $dtpDays): self
    {
        $this->dtpDays = $dtpDays;

        return $this;
    }

    public function getShopEmailSuffix(): string
    {
        return $this->shopEmailSuffix;
    }

    public function getCustomerEmailPrefix(): string
    {
        return $this->customerEmailPrefix;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;

        return $this->price;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

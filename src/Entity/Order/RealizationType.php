<?php

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
    protected $days = 0;

    /** @ORM\Column(type="integer") */
    protected $price = 0;

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

    public function getDays(): int
    {
        return $this->days;
    }

    public function setDays(int $days)
    {
        $this->days = $days;
        return $this->days;
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
        $description = $this->getName() . ' - ' . $this->days . ' dni roboczych';
        if ($this->getPrice() > 0) {
            $description .= ' (+' . ($this->getPrice() / 100) . ' PLN)';
        }

        return $description;
    }
}

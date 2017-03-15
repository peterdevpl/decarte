<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
    /** @ORM\Column(type="integer") */
    protected $sort = 0;

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $value)
    {
        $this->sort = $value;
    }
}

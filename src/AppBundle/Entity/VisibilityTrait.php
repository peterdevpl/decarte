<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait VisibilityTrait
{
    /** @ORM\Column(type="boolean", name="is_visible") */
    protected $isVisible = false;

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $flag)
    {
        $this->isVisible = $flag;
        return $this;
    }
}

<?php

namespace ProductBundle\Entity\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use ProductBundle\Entity\ProductImage;

class ProductImageListener
{
    protected $originalDirectory;

    public function __construct(string $originalDirectory)
    {
        $this->originalDirectory = $originalDirectory;
    }

    public function preRemove(ProductImage $object, LifecycleEventArgs $args)
    {
        @unlink($this->originalDirectory . '/' . $object->getBigName());
    }
}

<?php

namespace ProductBundle\Entity\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use ProductBundle\Entity\ProductImage;

class ProductImageListener
{
    protected $originalDirectory;
    protected $bigDirectory;
    protected $smallDirectory;

    public function __construct(string $originalDirectory, string $bigDirectory, string $smallDirectory)
    {
        $this->originalDirectory = $originalDirectory;
        $this->bigDirectory = $bigDirectory;
        $this->smallDirectory = $smallDirectory;
    }

    public function preRemove(ProductImage $object, LifecycleEventArgs $args)
    {
        @unlink($this->originalDirectory . '/' . $object->getOriginalName());
        @unlink($this->bigDirectory . '/' . $object->getBigName());
        @unlink($this->smallDirectory . '/' . $object->getSmallName());
    }
}

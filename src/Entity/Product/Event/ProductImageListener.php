<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Product\Event;

use Decarte\Shop\Entity\Product\ProductImage;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProductImageListener
{
    protected $originalDirectory;

    public function __construct(string $originalDirectory)
    {
        $this->originalDirectory = $originalDirectory;
    }

    public function preRemove(ProductImage $object, LifecycleEventArgs $args)
    {
        @unlink($this->originalDirectory . '/' . $object->getImageName());
    }
}

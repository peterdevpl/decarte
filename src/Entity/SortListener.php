<?php

namespace Decarte\Shop\Entity;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SortListener
{
    public function prePersist($object, LifecycleEventArgs $args)
    {
        $em = $args->getObjectManager();
        $sort = $em->getRepository(get_class($object))->fetchSortNumber($object);
        $object->setSort($sort);
    }
}

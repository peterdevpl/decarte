<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository\Order;

use Decarte\Shop\Entity\Order\DeliveryType;
use Decarte\Shop\Repository\SortableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeliveryTypeRepository extends ServiceEntityRepository
{
    use SortableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryType::class);
    }

    public function getDeliveryTypes($onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('dt');
        $queryBuilder
            ->orderBy('dt.sort', 'ASC');

        if ($onlyVisible) {
            $queryBuilder->where('dt.isVisible = :visible')->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    protected function getSortGroupField()
    {
        return null;
    }
}

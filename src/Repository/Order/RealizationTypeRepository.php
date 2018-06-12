<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository\Order;

use Decarte\Shop\Entity\Order\RealizationType;
use Decarte\Shop\Repository\SortableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RealizationTypeRepository extends ServiceEntityRepository
{
    use SortableRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RealizationType::class);
    }

    public function getRealizationTypes($onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('rt');
        $queryBuilder
            ->orderBy('rt.sort', 'ASC');

        if ($onlyVisible) {
            $queryBuilder->where('rt.isVisible = :visible')->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    protected function getSortGroupField()
    {
        return null;
    }
}

<?php

namespace Decarte\Shop\Repository\Product;

use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Repository\SortableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductTypeRepository extends ServiceEntityRepository
{
    use SortableRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductType::class);
    }

    public function getProductTypes($onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('pt');

        if ($onlyVisible) {
            $queryBuilder->where('pt.isVisible = :visible')->setParameter('visible', true);
        }
        
        $queryBuilder->orderBy('pt.id', 'ASC');
        $query = $queryBuilder->getQuery();
        
        return $query->getResult();
    }

    public function findBySlugName(string $slugName, bool $onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('pt');
        $queryBuilder
            ->where('pt.slugName = :type')
            ->setParameter(':type', $slugName)
            ->setMaxResults(1);

        if ($onlyVisible) {
            $queryBuilder
                ->andWhere('pt.isVisible = :visible')
                ->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return is_array($result) ? $result[0] : null;
    }

    protected function getSortGroupField()
    {
        return null;
    }
}

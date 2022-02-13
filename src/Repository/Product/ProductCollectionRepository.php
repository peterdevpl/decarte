<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository\Product;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Repository\SortableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductCollectionRepository extends ServiceEntityRepository
{
    use SortableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCollection::class);
    }

    public function getProductCollections($typeId, $onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('pc');
        $queryBuilder
            ->where('pc.productType = :type')
            ->setParameter(':type', $typeId)
            ->orderBy('pc.sort', 'ASC');

        if ($onlyVisible) {
            $queryBuilder->andWhere('pc.isVisible = :visible')->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function findBySlugName(string $typeSlugName, string $collectionSlugName, bool $onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('pc');
        $queryBuilder
            ->join('pc.productType', 'pt')
            ->where('pt.slugName = :type')
            ->andWhere('pc.slugName = :collection')
            ->setParameter(':type', $typeSlugName)
            ->setParameter(':collection', $collectionSlugName)
            ->setMaxResults(1);

        if ($onlyVisible) {
            $queryBuilder
                ->andWhere('pt.isVisible = :visible')
                ->andWhere('pc.isVisible = :visible')
                ->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return is_array($result) ? $result[0] : null;
    }

    protected function getSortGroupField()
    {
        return 'productType';
    }
}

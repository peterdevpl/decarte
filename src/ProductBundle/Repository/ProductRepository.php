<?php

namespace ProductBundle\Repository;

use AppBundle\Repository\SortableRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductType;

class ProductRepository extends EntityRepository
{
    use SortableRepositoryTrait;

    public function findDemos(ProductType $type, $onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->select(['p', 'ps', 'pc'])
            ->join('p.productSeries', 'ps')
            ->join('ps.productCollection', 'pc')
            ->where('pc.productType = :type')
            ->andWhere('p.hasDemo = :demo')
            ->setParameter(':type', $type->getId())
            ->setParameter(':demo', true);

        if ($onlyVisible) {
            $queryBuilder
                ->andWhere('p.isVisible = :visible')
                ->andWhere('ps.isVisible = :visible')
                ->andWhere('pc.isVisible = :visible')
                ->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    protected function getSortGroupField()
    {
        return 'productSeries';
    }
}

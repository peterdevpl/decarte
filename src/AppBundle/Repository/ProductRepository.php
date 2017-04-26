<?php
namespace AppBundle\Repository;

use AppBundle\Entity\ProductType;
use Doctrine\ORM\EntityRepository;

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

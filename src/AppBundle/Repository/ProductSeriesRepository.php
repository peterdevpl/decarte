<?php
namespace AppBundle\Repository;

use AppBundle\Entity\ProductCollection;
use Doctrine\ORM\EntityRepository;

class ProductSeriesRepository extends EntityRepository
{
    use SortableRepositoryTrait;

    public function getFromCollection(ProductCollection $collection, bool $onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('ps');
        $queryBuilder
            ->select(['ps', 'p', 'pi'])
            ->join('ps.products', 'p')
            ->join('p.images', 'pi')
            ->where('ps.productCollection = :collection')
            ->setParameter(':collection', $collection->getId())
            ->orderBy('ps.sort', 'ASC')
            ->addOrderBy('p.sort', 'ASC')
            ->addOrderBy('pi.sort', 'ASC');

        if ($onlyVisible) {
            $queryBuilder
                ->andWhere('ps.isVisible = :visible')
                ->andWhere('p.isVisible = :visible')
                ->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    protected function getSortGroupField()
    {
        return 'productCollection';
    }
}

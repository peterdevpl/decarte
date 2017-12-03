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
            ->andWhere('p.isDeleted = false')
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

    public function remove(Product $product)
    {
        foreach ($product->getImages() as $image) {
            $this->_em->remove($image);
        }
        $product->remove();
        $this->_em->flush();
    }

    protected function getSortGroupField()
    {
        return 'productSeries';
    }
}

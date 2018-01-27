<?php

namespace ProductBundle\Repository;

use AppBundle\Repository\SortableRepositoryTrait;
use Doctrine\Common\Collections\Criteria;
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
            ->select(['p', 'pc'])
            ->join('p.productCollection', 'pc')
            ->where('pc.productType = :type')
            ->andWhere('p.hasDemo = :demo')
            ->orderBy('pc.sort, p.sort', 'ASC')
            ->setParameter(':type', $type->getId())
            ->setParameter(':demo', true);

        if ($onlyVisible) {
            $queryBuilder
                ->andWhere('p.isVisible = :visible')
                ->andWhere('pc.isVisible = :visible')
                ->setParameter(':visible', true);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findAllVisibleProducts()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->select(['p', 'pc', 'pt'])
            ->join('p.productCollection', 'pc')
            ->join('pc.productType', 'pt')
            ->where('p.isVisible = :visible')
            ->andWhere('pc.isVisible = :visible')
            ->andWhere('pt.isVisible = :visible')
            ->orderBy('pt.sort, pc.sort, p.sort', 'ASC')
            ->setParameter(':visible', true);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findPrevious(Product $product)
    {
        return $this->findClosestSibling($product, 'lt', 'DESC');
    }

    public function findNext(Product $product)
    {
        return $this->findClosestSibling($product, 'gt', 'ASC');
    }

    protected function findClosestSibling(Product $product, string $expression, string $sortOrder)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isVisible', '1'))
            ->andWhere(Criteria::expr()->eq('productCollection', $product->getProductCollection()))
            ->andWhere(Criteria::expr()->$expression('sort', $product->getSort()))
            ->orderBy(['sort' => $sortOrder])
            ->setMaxResults(1);

        return $this->matching($criteria)->first();
    }

    protected function getSortGroupField()
    {
        return 'productCollection';
    }
}

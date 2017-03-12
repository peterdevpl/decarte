<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductCollectionRepository extends EntityRepository
{
    use SortableRepositoryTrait;

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
}

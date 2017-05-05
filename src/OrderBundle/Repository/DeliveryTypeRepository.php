<?php
namespace OrderBundle\Repository;

use AppBundle\Repository\SortableRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class DeliveryTypeRepository extends EntityRepository
{
    use SortableRepositoryTrait;

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

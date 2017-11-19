<?php
namespace OrderBundle\Repository;

use AppBundle\Repository\SortableRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class RealizationTypeRepository extends EntityRepository
{
    use SortableRepositoryTrait;

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

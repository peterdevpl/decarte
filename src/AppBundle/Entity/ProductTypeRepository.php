<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductTypeRepository extends EntityRepository
{
    public function getProductTypes($onlyVisible = true)
    {
        $queryBuilder = $this->createQueryBuilder('pt');

        if ($onlyVisible) {
            $queryBuilder->where('pt.is_visible = :visible')->setParameter('visible', true);
        }
        
        $queryBuilder->orderBy('pt.id', 'ASC');
        $query = $queryBuilder->getQuery();
        
        return $query->getResult();
    }
}

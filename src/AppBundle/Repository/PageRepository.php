<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function getPages()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->orderBy('p.title', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}

<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    use SortableRepositoryTrait;

    public function getAllProducts($number = 50)
    {
        $dql = 'SELECT p, ps, pc, pt FROM AppBundle:Product p JOIN p.productSeries ps JOIN ps.productCollection pc JOIN pc.productType pt ORDER BY p.id DESC';
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }
}

<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductSeriesRepository extends EntityRepository
{
    use SortableRepositoryTrait;
}
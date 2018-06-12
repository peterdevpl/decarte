<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository;

use Decarte\Shop\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function getPages()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->orderBy('p.title', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}

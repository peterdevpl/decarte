<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository;

use Decarte\Shop\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    public function findOneByName(string $slugName): ?Page
    {
        /** @var Page $page */
        $page = $this->findOneBy([
            'name' => $slugName,
        ]);

        return $page;
    }
}

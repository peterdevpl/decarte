<?php

declare(strict_types=1);

namespace Decarte\Shop\Repository;

use Decarte\Shop\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function findOneByName(string $slugName): ?BlogPost
	{
		/** @var BlogPost $post */
		$post = $this->findOneBy([
			'name' => $slugName,
		]);

		return $post;
	}
}

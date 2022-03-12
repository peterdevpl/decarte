<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="\Decarte\Shop\Repository\PageRepository") @ORM\Table(name="decarte_blog_posts")
 */
class BlogPost
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @phpstan-ignore-next-line
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @phpstan-ignore-next-line
     */
    private $name;

    /** @ORM\Column(type="string") */
    private $title;

    /** @ORM\Column(type="text") */
    private $contents;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable", name="created_at")
     * @phpstan-ignore-next-line
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime_immutable", name="updated_at", nullable=true)
     * @phpstan-ignore-next-line
     */
    private $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function setContents(string $contents)
    {
        $this->contents = $contents;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

<?php

namespace Decarte\Shop\Entity\Product;

use Decarte\Shop\Entity\SortableTrait;
use Decarte\Shop\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="\Decarte\Shop\Repository\Product\ProductTypeRepository")
 * @ORM\EntityListeners({"\Decarte\Shop\Entity\SortListener"})
 * @ORM\Table(name="decarte_product_types")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ProductType
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';

    /** @ORM\Column(type="string", name="slug_name") */
    protected $slugName = '';

    /** @ORM\Column(type="string") */
    protected $description = '';

    /** @ORM\Column(type="datetime", name="deleted_at", nullable=true) */
    protected $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="ProductCollection", mappedBy="productType", cascade={"remove"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $productCollections = null;
    
    public function __construct()
    {
        $this->productCollections = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSlugName(): string
    {
        return $this->slugName;
    }

    public function setSlugName(string $name)
    {
        $this->slugName = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = (string) $description;
        return $this;
    }
    
    public function getProductCollections()
    {
        return $this->productCollections;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

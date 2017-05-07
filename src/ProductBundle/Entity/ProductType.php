<?php

namespace ProductBundle\Entity;

use AppBundle\Entity\SortableTrait;
use AppBundle\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\ProductBundle\Repository\ProductTypeRepository")
 * @ORM\EntityListeners({"\AppBundle\Entity\SortListener"})
 * @ORM\Table(name="decarte_product_types")
 */
class ProductType
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';

    /** @ORM\Column(type="integer", name="minimum_quantity") */
    protected $minimumQuantity = 1;

    /** @ORM\Column(type="string", name="slug_name") */
    protected $slugName = '';

    /** @ORM\Column(type="string") */
    protected $description = '';

    /** @ORM\Column(type="boolean", name="has_front_page") */
    protected $hasFrontPage = false;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductCollection", mappedBy="productType")
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

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getProductCollections()
    {
        return $this->productCollections;
    }

    public function getMinimumQuantity(): int
    {
        return $this->minimumQuantity;
    }

    public function setMinimumQuantity(int $value)
    {
        $this->minimumQuantity = $value;
        return $this;
    }

    public function hasFrontPage()
    {
        return $this->hasFrontPage;
    }

    public function setHasFrontPage(bool $flag)
    {
        $this->hasFrontPage = $flag;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

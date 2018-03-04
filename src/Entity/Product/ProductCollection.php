<?php

namespace Decarte\Shop\Entity\Product;

use Decarte\Shop\Entity\SortableTrait;
use Decarte\Shop\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="\Decarte\Shop\Repository\Product\ProductCollectionRepository")
 * @ORM\EntityListeners({"\Decarte\Shop\Entity\SortListener"})
 * @ORM\Table(name="decarte_product_collections")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ProductCollection
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';
    
    /**
     * @ORM\Column(type="string", name="slug_name")
     * @Gedmo\Slug(fields={"name"})
     */
    protected $slugName = '';

    /** @ORM\Column(type="string", name="title_seo") */
    protected $titleSEO = '';

    /** @ORM\Column(type="integer", name="minimum_quantity") */
    protected $minimumQuantity = 1;

    /** @ORM\Column(type="text", name="short_description") */
    protected $shortDescription = '';
    
    /** @ORM\Column(type="text") */
    protected $description = '';
    
    /** @ORM\Column(type="string", name="image_name", nullable=true) */
    protected $imageName = '';

    /** @ORM\Column(type="datetime", name="deleted_at", nullable=true) */
    protected $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="ProductType", inversedBy="productCollections")
     * @ORM\JoinColumn(name="product_type_id", referencedColumnName="id")
     */
    protected $productType = null;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productCollection", cascade={"remove"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $products = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
    public function getProductType(): ProductType
    {
        return $this->productType;
    }
    
    public function setProductType(ProductType $type)
    {
        $this->productType = $type;
        return $this;
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

    public function getTitleSEO(): string
    {
        return $this->titleSEO;
    }

    public function setTitleSEO(string $title)
    {
        $this->titleSEO = $title;
        return $this;
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

    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
    
    public function setShortDescription($description)
    {
        $this->shortDescription = (string) $description;
        return $this;
    }

    public function getImageName()
    {
        return $this->imageName;
    }
    
    public function setImageName($image)
    {
        $this->imageName = $image;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->isVisible && $this->getProductType()->isVisible();
    }

    public function __toString()
    {
        return $this->getName();
    }
}

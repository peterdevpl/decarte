<?php

namespace ProductBundle\Entity;

use AppBundle\Entity\SortableTrait;
use AppBundle\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="\ProductBundle\Repository\ProductRepository")
 * @ORM\EntityListeners({"\AppBundle\Entity\SortListener"})
 * @ORM\Table(name="decarte_products")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Product
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';
    
    /** @ORM\Column(type="integer") */
    protected $price = 0;
    
    /** @ORM\Column(type="text") */
    protected $description = '';
    
    /** @ORM\Column(type="text", name="description_seo") */
    protected $descriptionSEO = '';
    
    /**
     * @ORM\ManyToOne(targetEntity="ProductCollection", inversedBy="products")
     * @ORM\JoinColumn(name="product_collection_id", referencedColumnName="id")
     */
    protected $productCollection = null;
    
    /** @ORM\Column(type="boolean", name="has_demo") */
    protected $hasDemo = false;
    
    /** @ORM\Column(type="integer", name="last_changed_at") */
    protected $lastChangedAt = 0;

    /** @ORM\Column(type="datetime", name="deleted_at", nullable=true) */
    protected $deletedAt;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ProductImage", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true
 *     )
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
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
    
    public function getPrice(): int
    {
        return $this->price;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description ?? '';
        return $this;
    }
    
    public function getDescriptionSEO(): string
    {
        return $this->descriptionSEO;
    }
    
    public function setDescriptionSEO($description)
    {
        $this->descriptionSEO = $description ?? '';
        return $this;
    }
    
    public function getProductCollection(): ProductCollection
    {
        return $this->productCollection;
    }
    
    public function setProductCollection(ProductCollection $collection)
    {
        $this->productCollection = $collection;
        return $this;
    }
    
    public function hasDemo(): bool
    {
        return $this->hasDemo;
    }
    
    public function setHasDemo(bool $flag)
    {
        $this->hasDemo = $flag;
        return $this;
    }

    public function getMinimumQuantity(): int
    {
        return $this->getProductCollection()->getMinimumQuantity();
    }

    /**
     * @return ProductImage[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function addImage(ProductImage $image)
    {
        $image->setProduct($this);
        $this->images->add($image);
        return $this;
    }

    public function removeImage(ProductImage $image)
    {
        $this->images->removeElement($image);
        $image->setProduct(null);
        return $this;
    }

    /**
     * @return ProductImage|bool
     */
    public function getCoverImage()
    {
        return $this->images->first();
    }

    public function isVisible(): bool
    {
        return $this->isVisible && $this->getProductCollection()->isVisible();
    }

    public function __toString()
    {
        return $this->getProductCollection()->getName() . ' - ' . $this->getName();
    }
}

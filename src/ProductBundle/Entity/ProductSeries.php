<?php

namespace ProductBundle\Entity;

use AppBundle\Entity\SortableTrait;
use AppBundle\Entity\VisibilityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\ProductBundle\Repository\ProductSeriesRepository")
 * @ORM\EntityListeners({"\AppBundle\Entity\SortListener"})
 * @ORM\Table(name="decarte_product_series")
 */
class ProductSeries
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';
    
    /** @ORM\Column(type="text") */
    protected $description = '';
    
    /**
     * @ORM\Column(type="string", name="image_name", nullable=true)
     */
    protected $imageName = '';

    /** @ORM\Column(type="integer", name="last_changed_at") */
    protected $lastChangedAt = 0;
    
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productSeries")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $products = null;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCollection", inversedBy="productSeries")
     * @ORM\JoinColumn(name="product_collection_id", referencedColumnName="id")
     */
    protected $productCollection;
    
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    
    public function getProducts()
    {
        return $this->products;
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
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = (string) $description;
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

    public function __toString()
    {
        return $this->getName();
    }
}

<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ProductCollectionRepository")
 * @ORM\Table(name="decarte_product_collections")
 */
class ProductCollection
{
    use SortableTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';
    
    /** @ORM\Column(type="string", name="slug_name") */
    protected $slugName = '';
    
    /** @ORM\Column(type="text", name="short_description") */
    protected $shortDescription = '';
    
    /** @ORM\Column(type="text") */
    protected $description = '';
    
    /** @ORM\Column(type="boolean", name="is_visible") */
    protected $isVisible = false;
    
    /** @ORM\Column(type="string", name="image_name", nullable=true) */
    protected $imageName = '';

    /**
     * @ORM\ManyToOne(targetEntity="ProductType", inversedBy="productCollections")
     * @ORM\JoinColumn(name="product_type_id", referencedColumnName="id")
     */
    protected $productType = null;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductSeries", mappedBy="productCollection")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $productSeries = null;
   
    public function __construct()
    {
        $this->productSeries = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getProductSeries()
    {
        return $this->productSeries;
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
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
    
    public function setShortDescription(string $description)
    {
        $this->shortDescription = $description;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }
    
    public function setIsVisible(bool $flag)
    {
        $this->isVisible = $flag;
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
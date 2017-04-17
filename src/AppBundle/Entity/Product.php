<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\ProductRepository") @ORM\Table(name="decarte_products")
 * @ORM\EntityListeners({"SortListener"})
 */
class Product
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id = 0;
    
    /** @ORM\Column(type="string") */
    protected $name = '';
    
    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $price = 0;
    
    /** @ORM\Column(type="text") */
    protected $description = '';
    
    /** @ORM\Column(type="text", name="description_seo") */
    protected $descriptionSEO = '';
    
    /**
     * @ORM\ManyToOne(targetEntity="ProductSeries", inversedBy="products")
     * @ORM\JoinColumn(name="product_series_id", referencedColumnName="id")
     */
    protected $productSeries = null;
    
    /** @ORM\Column(type="boolean", name="has_demo") */
    protected $hasDemo = false;
    
    /** @ORM\Column(type="integer", name="last_changed_at") */
    protected $lastChangedAt = 0;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", fetch="EAGER", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $images = null;

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
    
    public function getPrice()
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
    
    public function getProductSeries(): ProductSeries
    {
        return $this->productSeries;
    }
    
    public function setProductSeries(ProductSeries $series)
    {
        $this->productSeries = $series;
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

    public function getImages()
    {
        return $this->images;
    }

    public function addImage(ProductImage $image)
    {
        $this->images->add($image);
    }

    public function removeImage(ProductImage $image)
    {
        $this->images->removeElement($image);
    }

    public function getCoverImage()
    {
        return $this->images->first();
    }
}

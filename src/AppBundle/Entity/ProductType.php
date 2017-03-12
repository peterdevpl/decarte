<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ProductTypeRepository") @ORM\Table(name="decarte_product_types")
 */
class ProductType
{
    use SortableTrait;
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id;
    
    /** @ORM\Column(type="string") */
    protected $name;
    
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
    
    public function getProductCollections()
    {
        return $this->productCollections;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}

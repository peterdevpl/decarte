<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({"\ProductBundle\Entity\Event\ProductImageListener"})
 * @ORM\Table(name="decarte_product_images")
 */
class ProductImage
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product = null;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $sort = 0;

    /**
     * @ORM\Column(type="string", name="big_name")
     */
    protected $bigName;
    
    /**
     * @ORM\Column(type="string", name="small_name")
     */
    protected $smallName;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }
    
    public function setSort(int $sort)
    {
        $this->sort = $sort;
        return $this;
    }

    public function getBigName()
    {
        return $this->bigName;
    }
    
    public function setBigName($name)
    {
        $this->bigName = $name;
        return $this;
    }

    public function getSmallName()
    {
        return $this->smallName;
    }
    
    public function setSmallName($name)
    {
        $this->smallName = $name;
        return $this;
    }
}

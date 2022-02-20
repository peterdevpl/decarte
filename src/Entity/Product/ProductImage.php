<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({"\Decarte\Shop\Entity\Product\Event\ProductImageListener"})
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
     * @ORM\Column(type="string", name="image_name")
     */
    protected $imageName;

    public function __construct(Product $product, int $sort, string $imageName)
    {
        $this->product = $product;
        $this->sort = $sort;
        $this->imageName = $imageName;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product)
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

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName($name)
    {
        $this->imageName = $name;

        return $this;
    }
}

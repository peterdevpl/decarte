<?php

declare(strict_types=1);

namespace Decarte\Shop\Entity\Product;

use Decarte\Shop\Entity\VisibilityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="\Decarte\Shop\Repository\Product\ProductCollectionRepository")
 * @ORM\Table(name="decarte_product_collections")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 */
class ProductCollection
{
    use VisibilityTrait;

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private $id = 0;

    /** @ORM\Column(type="string") */
    private $name = '';

    /**
     * @ORM\Column(type="string", name="slug_name")
     * @Gedmo\Slug(fields={"name"})
     */
    private $slugName;

    /** @ORM\Column(type="string", name="title_seo") */
    private $titleSEO = '';

    /** @ORM\Column(type="integer", name="minimum_quantity") */
    private $minimumQuantity = 1;

    /** @ORM\Column(type="text", name="short_description") */
    private $shortDescription = '';

    /** @ORM\Column(type="text") */
    private $description = '';

    /** @Vich\UploadableField(mapping="product_collection", fileNameProperty="imageName") */
    private $imageFile;

    /** @ORM\Column(type="string", name="image_name", nullable=true) */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @phpstan-ignore-next-line
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     * @phpstan-ignore-next-line
     */
    private $deletedAt;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="ProductType", inversedBy="productCollections")
     * @ORM\JoinColumn(name="product_type_id", referencedColumnName="id")
     */
    private $productType = null;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productCollection", cascade={"remove"})
     * @ORM\OrderBy({"sort": "ASC"})
     */
    private $products = null;

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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
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

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $value)
    {
        $this->sort = $value;
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

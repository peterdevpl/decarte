<?php

namespace AppBundle\Menu;

use ProductBundle\Repository\ProductCollectionRepository;
use ProductBundle\Repository\ProductTypeRepository;

class Menu
{
    private $productTypeRepository;
    private $productCollectionRepository;
    private $entries;

    public function __construct(
        ProductTypeRepository $productTypeRepository,
        ProductCollectionRepository $productCollectionRepository
    ) {
        $this->productTypeRepository = $productTypeRepository;
        $this->productCollectionRepository = $productCollectionRepository;
    }

    public function getEntries()
    {
        if (is_array($this->entries)) {
            return $this->entries;
        }

        $this->entries = [
            new MenuEntry('shop_order_samples', 'Zamów próbki'),
        ];

        foreach ($this->productTypeRepository->getProductTypes() as $productType) {
            $this->entries[] = new MenuEntry(
                'shop_list_collections',
                $productType->getName(),
                ['type' => $productType->getSlugName()]
            );

            foreach ($this->productCollectionRepository->getProductCollections($productType->getId()) as $collection) {
                $this->entries[] = new MenuEntry(
                    'shop_view_collection',
                    $collection->getName(),
                    ['type' => $productType->getSlugName(), 'slugName' => $collection->getSlugName()],
                    'subitem'
                );
            }
        }

        return $this->entries;
    }
}

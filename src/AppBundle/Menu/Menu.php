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
            new MenuEntry('shop_list_collections', 'Zaproszenia ślubne', ['type' => 'zaproszenia-slubne']),
            new MenuEntry('shop_order_samples', 'Zamów próbki'),
        ];

        $type = $this->productTypeRepository->findBySlugName('dodatki');
        if (!$type) {
            return $this->entries;
        }

        $collections = $this->productCollectionRepository->getProductCollections($type->getId());
        foreach ($collections as $collection) {
            $this->entries[] = new MenuEntry(
                'shop_view_collection',
                $collection->getName(),
                ['type' => $type->getSlugName(), 'slugName' => $collection->getSlugName()]
            );
        }

        return $this->entries;
    }
}

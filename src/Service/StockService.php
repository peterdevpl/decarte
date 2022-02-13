<?php

declare(strict_types=1);

namespace Decarte\Shop\Service;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Exception\StockTooSmallException;
use Doctrine\Persistence\ManagerRegistry;

final class StockService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @throws StockTooSmallException
     */
    public function checkAndUpdateProducts(Order $order): void
    {
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            if (!$product->hasStockSet()) {
                continue;
            }

            if ($product->getStock() < $item->getQuantity()) {
                $e = new StockTooSmallException();
                $e->setContext($product, $item->getQuantity());
                throw $e;
            }

            $product->setStock($product->getStock() - $item->getQuantity());
            $this->doctrine->getManager()->persist($product);
        }
    }
}

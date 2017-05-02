<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartRepository
{
    /** @var SessionInterface */
    protected $session;

    /** @var ProductRepository */
    protected $productRepository;

    /** @var Cart */
    protected $cart;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function getCart()
    {
        if (!$this->cart) {
            $serializedCart = $this->session->get('cart');
            if ($serializedCart) {
                $this->cart = $this->deserialize($serializedCart);
            } else {
                $this->cart = new Cart($this->session->getId());
            }
        }

        return $this->cart;
    }

    protected function deserialize(string $serializedCart)
    {
        $cartArray = json_decode($serializedCart, true);
        if (!$cartArray) {
            $cart = new Cart($this->session->getId());
        } else {
            $cart = new Cart($cartArray['id']);

            foreach ($cartArray['items'] as $itemArray) {
                $product = $this->productRepository->find($itemArray['productId']);
                $item = new CartItem($product, $itemArray['quantity'], $itemArray['minimumQuantity']);
                $item->setUnitPrice($itemArray['unitPrice']);
                $cart->addItem($item);
            }
        }

        return $cart;
    }

    public function persist()
    {
        $this->session->set('cart', json_encode($this->getCart()));
    }
}

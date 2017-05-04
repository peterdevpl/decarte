<?php

namespace AppBundle\Controller;

use AppBundle\Cart\Entity\CartItem;
use AppBundle\Cart\Repository\CartRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    /**
     * @Route("/koszyk", name="cart_index")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('AppBundle:Product');

        $repository = new CartRepository(new Session(), $productRepository);
        $cart = $repository->getCart();

        if (count($cart->getItems())) {
            $view = 'cart/index.html.twig';
            $parameters = ['cart' => $cart];
        } else {
            $view = 'cart/empty.html.twig';
            $parameters = [];
        }

        return $this->render($view, $parameters);
    }

    /**
     * @Route("/koszyk/dodaj", name="cart_add_item")
     * @param Request $request
     * @return Response
     */
    public function addItemAction(Request $request)
    {
        $productId = (int) $request->get('product_id');
        $quantity = (int) $request->get('quantity');

        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('AppBundle:Product');
        $product = $productRepository->find($productId);

        if (!$product ||
            !$product->isVisible() ||
            !$product->getProductSeries()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->getProductType()->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $minimumQuantity = $product->getProductSeries()->getProductCollection()->getProductType()->getMinimumQuantity();
        $cartRepository = new CartRepository(new Session(), $productRepository);

        try {
            $cartRepository->getCart()->addItem(new CartItem($product, $quantity, $minimumQuantity));
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', 'Minimalna liczba sztuk to ' . $minimumQuantity);

            return $this->redirectToRoute('shop_view_product', [
                'type' => $product->getProductSeries()->getProductCollection()->getProductType()->getSlugName(),
                'slugName' => $product->getProductSeries()->getProductCollection()->getSlugName(),
                'id' => $product->getId(),
            ]);
        }

        $cartRepository->persist();

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/koszyk/zapisz", name="cart_save")
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('AppBundle:Product');
        $cartRepository = new CartRepository(new Session(), $productRepository);

        $cart = $cartRepository->getCart();

        $quantities = $request->get('quantity');
        foreach ($quantities as $productId => $quantity) {
            $item = $cart->getItem((int) $productId);
            if ($item) {
                $item->setQuantity((int) $quantity);
            }
        }

        $cartRepository->persist();

        if (!empty($request->get('save'))) {
            $route = 'cart_index';
        } else {
            $route = 'put_order';
        }

        return $this->redirectToRoute($route);
    }
}

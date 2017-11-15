<?php

namespace OrderBundle\Controller;

use OrderBundle\Exception\QuantityTooSmallException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/koszyk", name="cart_index")
     * @return Response
     */
    public function indexAction()
    {
        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();

        if (count($order->getItems())) {
            $view = 'cart/index.html.twig';
            $parameters = ['order' => $order];
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
        $productRepository = $em->getRepository('ProductBundle:Product');
        $product = $productRepository->find($productId);

        if (!$product ||
            !$product->isVisible() ||
            !$product->getProductSeries()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->getProductType()->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $orderRepository = $this->get('temporary_order_repository');
        $order = $orderRepository->getOrder();

        try {
            $order->addItem($product, $quantity, $product->getPrice());
        } catch (QuantityTooSmallException $e) {
            $this->addFlash('error', 'Minimalna liczba sztuk to ' .
                $e->getProduct()->getMinimumQuantity());

            return $this->redirectToRoute('shop_view_product', [
                'type' => $product->getProductSeries()->getProductCollection()->getProductType()->getSlugName(),
                'slugName' => $product->getProductSeries()->getProductCollection()->getSlugName(),
                'id' => $product->getId(),
            ]);
        }

        $orderRepository->persist($order);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/koszyk/zapisz", name="cart_save")
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $orderRepository = $this->get('temporary_order_repository');
        $order = $orderRepository->getOrder();

        $quantities = $request->get('quantity');
        foreach ($quantities as $productId => $quantity) {
            $item = $order->getItemById((int) $productId);
            try {
                $item->setQuantity((int)$quantity);
            } catch (QuantityTooSmallException $e) {
                $this->addFlash('error', sprintf('Minimalna liczba sztuk produktu %s to %i',
                    $e->getProduct()->getName(),
                    $e->getProduct()->getMinimumQuantity()
                ));
            }
        }

        $orderRepository->persist($order);

        if (!empty($request->get('save'))) {
            $route = 'cart_index';
        } else {
            $route = 'order_shipping_details';
        }

        return $this->redirectToRoute($route);
    }
}

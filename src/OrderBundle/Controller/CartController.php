<?php

namespace OrderBundle\Controller;

use OrderBundle\Entity\Order;
use OrderBundle\Exception\QuantityTooSmallException;
use OrderBundle\Form\Cart\CartType;
use OrderBundle\Repository\SessionOrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/koszyk", name="cart_index")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();

        if (count($order->getItems())) {
            return $this->showCart($repository, $order, $request);
        } else {
            $view = 'OrderBundle:cart:empty.html.twig';
            $parameters = [];
        }

        return $this->render($view, $parameters);
    }

    protected function showCart(SessionOrderRepository $repository, Order $order, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $realizationTypes = $em->getRepository('OrderBundle:RealizationType')->getRealizationTypes();
        $deliveryTypes = $em->getRepository('OrderBundle:DeliveryType')->getDeliveryTypes();

        $form = $this->createForm(CartType::class, $order, [
            'realization_types' => $realizationTypes,
            'delivery_types' => $deliveryTypes,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $repository->persist($order);

            $route = $form->has('save_and_order') && $form->get('save_and_order')->isClicked() ?
                'order_shipping_details' : 'cart_index';

            return $this->redirectToRoute($route, [], 303);
        }

        return $this->render('OrderBundle:cart:index.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
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

        if (!$product || !$product->isVisible()) {
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
                'type' => $product->getProductCollection()->getProductType()->getSlugName(),
                'slugName' => $product->getProductCollection()->getSlugName(),
                'id' => $product->getId(),
            ]);
        }

        $orderRepository->persist($order);

        return $this->redirectToRoute('cart_index');
    }
}

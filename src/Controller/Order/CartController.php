<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Exception\QuantityTooSmallException;
use Decarte\Shop\Form\Cart\CartType;
use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\RealizationTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/koszyk", name="cart_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(
        Request $request,
        SessionOrderRepository $orderRepository,
        RealizationTypeRepository $realizationTypeRepository,
        DeliveryTypeRepository $deliveryTypeRepository
    ): Response {
        $order = $orderRepository->getOrder();

        if (count($order->getItems())) {
            return $this->showCart(
                $orderRepository,
                $realizationTypeRepository,
                $deliveryTypeRepository,
                $order,
                $request
            );
        } else {
            $view = 'cart/empty.html.twig';
            $parameters = [];
        }

        return $this->render($view, $parameters);
    }

    protected function showCart(
        SessionOrderRepository $repository,
        RealizationTypeRepository $realizationTypeRepository,
        DeliveryTypeRepository $deliveryTypeRepository,
        Order $order,
        Request $request
    ): Response {
        $realizationTypes = $realizationTypeRepository->getRealizationTypes();
        $deliveryTypes = $deliveryTypeRepository->getDeliveryTypes();

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

        return $this->render('cart/index.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    /**
     * @Route("/koszyk/dodaj", name="cart_add_item")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addItemAction(
        Request $request,
        ProductRepository $productRepository,
        SessionOrderRepository $orderRepository
    ): Response {
        $productId = (int) $request->get('product_id');
        $quantity = (int) $request->get('quantity');

        $product = $productRepository->find($productId);
        if (!$product || !$product->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

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

    /**
     * @Route("/koszyk/usun/{productId}", name="cart_delete_item", requirements={"productId": "\d+"})
     *
     * @param int $productId
     *
     * @return Response
     */
    public function deleteItemAction(
        int $productId,
        ProductRepository $productRepository,
        SessionOrderRepository $orderRepository
    ): Response {
        $product = $productRepository->find($productId);
        $order = $orderRepository->getOrder();
        $order->removeProduct($product);
        $orderRepository->persist($order);

        return $this->redirectToRoute('cart_index');
    }
}

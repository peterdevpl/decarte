<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Exception\QuantityTooSmallException;
use Decarte\Shop\Exception\StockTooSmallException;
use Decarte\Shop\Form\Cart\CartType;
use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\RealizationTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CartController extends AbstractController
{
    /**
     * @Route("/koszyk", name="cart_index")
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

    private function showCart(
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

            $route = 'cart_index';
            if ($form->has('save_and_order')) {
                /** @var ClickableInterface $button */
                $button = $form->get('save_and_order');
                if ($button->isClicked()) {
                    $route = 'order_shipping_details';
                }
            }

            return $this->redirectToRoute($route, [], 303);
        }

        return $this->render('cart/index.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    /**
     * @Route("/koszyk/dodaj", name="cart_add_item")
     */
    public function addItemAction(
        Request $request,
        ProductRepository $productRepository,
        SessionOrderRepository $orderRepository
    ): Response {
        $productId = (int)$request->get('product_id');
        $quantity = (int)$request->get('quantity');

        /** @var ?Product $product */
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
        } catch (StockTooSmallException $e) {
            $this->addFlash('error', 'Liczba dostępnych sztuk tego produktu to ' .
                $e->getStock());

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
     */
    public function deleteItemAction(
        int $productId,
        ProductRepository $productRepository,
        SessionOrderRepository $orderRepository
    ): Response {
        /** @var ?Product $product */
        $product = $productRepository->find($productId);
        $order = $orderRepository->getOrder();
        $order->removeProduct($product);
        $orderRepository->persist($order);

        return $this->redirectToRoute('cart_index');
    }
}

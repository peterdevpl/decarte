<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Form\Order\OrderSamplesType;
use Decarte\Shop\Repository\Order\SessionSamplesOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\SamplesOrderMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SamplesOrderController extends AbstractController
{
    /**
     * @Route("/zamow-probki", name="shop_order_samples")
     */
    public function orderSamplesAction(
        Request $request,
        SessionSamplesOrderRepository $samplesOrderRepository,
        ProductTypeRepository $productTypeRepository,
        ProductRepository $productRepository,
        SamplesOrderMailer $mailer
    ): Response {
        $order = $samplesOrderRepository->getOrder();
        /** @var ProductType $productType */
        $productType = $productTypeRepository->find(1);
        $products = $productRepository->findDemos($productType);

        $form = $this->createForm(OrderSamplesType::class, $order, [
            'products' => $products,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $mailer->sendSamplesOrderEmailToShop($order);
            $mailer->sendSamplesOrderEmailToCustomer($order);
            $samplesOrderRepository->clear();

            return $this->redirectToRoute('shop_order_samples_confirmation');
        }

        return $this->render('samples/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/zamow-probki/dodaj", name="shop_add_sample")
     */
    public function addSampleAction(
        Request $request,
        SessionSamplesOrderRepository $samplesOrderRepository,
        ProductRepository $productRepository
    ): Response {
        /** @var Product $product */
        $product = $productRepository->find($request->get('product_id'));
        $order = $samplesOrderRepository->getOrder();

        if ($order->getItems()->count() < (int) $this->getParameter('samples_count')) {
            $order->addItem($product);
            $samplesOrderRepository->persist($order);
        }

        return $this->redirectToRoute('shop_order_samples');
    }

    /**
     * @Route("/zamow-probki/potwierdzenie", name="shop_order_samples_confirmation")
     */
    public function orderSamplesConfirmationAction(): Response
    {
        return $this->render('samples/confirmation.html.twig');
    }
}

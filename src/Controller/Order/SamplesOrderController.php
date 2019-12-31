<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Form\Order\OrderSamplesType;
use Decarte\Shop\Repository\Order\DeliveryTypeRepository;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\Payment\PayU;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SamplesOrderController extends AbstractController
{
    /**
     * @Route("/zamow-probki", name="shop_order_samples")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function orderSamplesAction(
        Request $request,
        SessionOrderRepository $orderRepository,
        ProductTypeRepository $productTypeRepository,
        ProductRepository $productRepository,
        DeliveryTypeRepository $deliveryTypeRepository,
        \Swift_Mailer $mailer,
        PayU $payu
    ): Response {
        $order = $orderRepository->getOrder(SessionOrderRepository::SAMPLES);
        $order->setType(Order::SAMPLES);
        /** @var ProductType $productType */
        $productType = $productTypeRepository->find(1);
        $products = $productRepository->findDemos($productType);
        $deliveryTypes = $deliveryTypeRepository->getDeliveryTypesForSamples();

        $form = $this->createForm(OrderSamplesType::class, $order, [
            'products' => $products,
            'delivery_types' => $deliveryTypes,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->saveOrder($form->getData(), $request, $orderRepository, $mailer, $payu);
        }

        return $this->render('samples/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function saveOrder(
        Order $order,
        Request $request,
        SessionOrderRepository $orderRepository,
        \Swift_Mailer $mailer,
        PayU $payu
    ): Response {
        $order->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->sendSamplesOrderEmailToShop($order, $mailer);
        $this->sendSamplesOrderEmailToCustomer($order, $mailer);
        $orderRepository->clear(SessionOrderRepository::SAMPLES);

        if ('PayU' === $order->getDeliveryType()->getShortName()) {
            return $payu->createOrder($request, $order);
        }

        return $this->redirectToRoute('shop_order_samples_confirmation');
    }

    /**
     * @Route("/zamow-probki/dodaj", name="shop_add_sample")
     */
    public function addSampleAction(
        Request $request,
        SessionOrderRepository $orderRepository,
        ProductRepository $productRepository
    ): Response {
        /** @var Product $product */
        $product = $productRepository->find($request->get('product_id'));
        $order = $orderRepository->getOrder(SessionOrderRepository::SAMPLES);

        if ($order->getItems()->count() < $this->getParameter('samples_count')) {
            $order->addItem($product, 1, 0);
            $orderRepository->persist($order, SessionOrderRepository::SAMPLES);
        }

        return $this->redirectToRoute('shop_order_samples');
    }

    /**
     * @Route("/zamow-probki/potwierdzenie", name="shop_order_samples_confirmation")
     *
     * @return Response
     */
    public function orderSamplesConfirmationAction(): Response
    {
        return $this->render('samples/confirmation.html.twig');
    }

    private function sendSamplesOrderEmailToShop(Order $order, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message())
            ->setSubject('ZAMÓWIENIE WWW - PRÓBKI')
            ->setTo($this->getParameter('admin_mail'))
            ->setFrom([$this->getParameter('admin_mail') => $order->getName()])
            ->setReplyTo($order->getEmail())
            ->setBody(
                $this->renderView('samples/mail/shop.html.twig', [
                    'order' => $order,
                ]),
                'text/html'
            );

        $mailer->send($message);
    }

    private function sendSamplesOrderEmailToCustomer(Order $order, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message())
            ->setSubject('Zamówienie decarte.com.pl - próbki')
            ->setTo($order->getEmail())
            ->setFrom([$this->getParameter('admin_mail') => 'Sklep ślubny decARTe.com.pl'])
            ->setReplyTo($this->getParameter('admin_mail'))
            ->setBody(
                $this->renderView('samples/mail/customer.html.twig', [
                    'order' => $order,
                ]),
                'text/html'
            );

        $mailer->send($message);
    }
}

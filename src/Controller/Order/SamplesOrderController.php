<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Order\Samples\Order;
use Decarte\Shop\Entity\Order\Samples\OrderItem;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Form\Order\OrderSamplesType;
use Decarte\Shop\Repository\Order\SessionSamplesOrderRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SamplesOrderController extends Controller
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
        SessionSamplesOrderRepository $samplesOrderRepository,
        ProductTypeRepository $productTypeRepository,
        ProductRepository $productRepository,
        \Swift_Mailer $mailer
    ): Response {
        $order = $samplesOrderRepository->getOrder();
        $productType = $productTypeRepository->find(1);
        $products = $productRepository->findDemos($productType);

        $form = $this->createForm(OrderSamplesType::class, $order, [
            'products' => $products,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $this->sendSamplesOrderEmailToShop($order, $mailer);
            $this->sendSamplesOrderEmailToCustomer($order, $mailer);
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

        if ($order->getItems()->count() < $this->getParameter('samples_count')) {
            $order->addItem($product);
            $samplesOrderRepository->persist($order);
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

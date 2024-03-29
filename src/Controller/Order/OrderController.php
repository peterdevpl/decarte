<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Exception\StockTooSmallException;
use Decarte\Shop\Form\Order\ShippingDetailsType;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Service\OrderMailer;
use Decarte\Shop\Service\Payment\PayU;
use Decarte\Shop\Service\StockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class OrderController extends AbstractController
{
    /**
     * @Route("/zloz-zamowienie/dane-wysylki", name="order_shipping_details")
     */
    public function shippingDetailsAction(Request $request, SessionOrderRepository $orderRepository): Response
    {
        $order = $orderRepository->getOrder();

        $form = $this->createForm(ShippingDetailsType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $orderRepository->persist($order);

            return $this->redirectToRoute('order_summary', [], 303);
        }

        return $this->render('order/shipping-details.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/podsumowanie-zamowienia", name="order_summary")
     */
    public function summaryAction(SessionOrderRepository $orderRepository): Response
    {
        return $this->render('order/summary.html.twig', [
            'order' => $orderRepository->getOrder(),
        ]);
    }

    /**
     * @Route("/zapisz-zamowienie", name="order_save")
     */
    public function saveAction(
        Request $request,
        SessionOrderRepository $orderRepository,
        OrderMailer $orderMailer,
        PayU $payu,
        StockService $stock,
        TranslatorInterface $translator
    ): Response {
        $order = $orderRepository->getOrder();
        try {
            $stock->checkAndUpdateProducts($order);
        } catch (StockTooSmallException $e) {
            $this->addFlash('error', $translator->trans('order.out_of_stock_error'));

            return $this->redirectToRoute('cart_index', [], 303);
        }

        $order->setCreatedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $orderMailer->sendEmailToShop($order);
        $orderMailer->sendEmailToCustomer($order);

        $orderRepository->clear();

        if ('PayU' === $order->getDeliveryType()->getShortName()) {
            return $payu->createOrder($request, $order);
        }

        return $this->redirectToRoute('order_confirmation', [], 303);
    }

    /**
     * @Route("/potwierdzenie-zamowienia", name="order_confirmation")
     */
    public function confirmationAction(): Response
    {
        return $this->render('order/confirmation.html.twig');
    }

    /**
     * @Route("/payu", name="payu_notification")
     */
    public function payuNotificationAction(Request $request, PayU $payu): Response
    {
        return $payu->processNotification($request);
    }
}

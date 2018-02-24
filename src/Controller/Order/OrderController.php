<?php

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Form\Order\ShippingDetailsType;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Service\OrderMailer;
use Decarte\Shop\Service\Payment\PayU;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @Route("/zloz-zamowienie/dane-wysylki", name="order_shipping_details")
     * @param Request $request
     * @return Response
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
     * @return Response
     */
    public function summaryAction(SessionOrderRepository $orderRepository): Response
    {
        return $this->render('order/summary.html.twig', [
            'order' => $orderRepository->getOrder(),
        ]);
    }

    /**
     * @Route("/zapisz-zamowienie", name="order_save")
     * @param Request $request
     * @return Response
     */
    public function saveAction(
        Request $request,
        SessionOrderRepository $orderRepository,
        OrderMailer $orderMailer,
        PayU $payu
    ): Response {
        $order = $orderRepository->getOrder();
        $order->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $orderMailer->sendEmailToShop($order);
        $orderMailer->sendEmailToCustomer($order);

        $orderRepository->clear();

        if ($order->getDeliveryType()->getShortName() === 'PayU') {
            return $payu->createOrder($request, $order);
        }

        return $this->redirectToRoute('order_confirmation', [], 303);
    }

    /**
     * @Route("/potwierdzenie-zamowienia", name="order_confirmation")
     * @return Response
     */
    public function confirmationAction(): Response
    {
        return $this->render('order/confirmation.html.twig');
    }

    /**
     * @Route("/payu", name="payu_notification")
     * @param Request $request
     * @return Response
     */
    public function payuNotificationAction(Request $request, PayU $payu): Response
    {
        return $payu->processNotification($request);
    }
}

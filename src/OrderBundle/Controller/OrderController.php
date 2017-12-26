<?php

namespace OrderBundle\Controller;

use OrderBundle\Form\ShippingDetailsType;
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
    public function shippingDetailsAction(Request $request)
    {
        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();

        $form = $this->createForm(ShippingDetailsType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $repository->persist($order);

            return $this->redirectToRoute('order_summary', [], 303);
        }

        return $this->render('OrderBundle:order:shipping-details.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/podsumowanie-zamowienia", name="order_summary")
     * @return Response
     */
    public function summaryAction()
    {
        return $this->render('OrderBundle:order:summary.html.twig', [
            'order' => $this->get('temporary_order_repository')->getOrder(),
        ]);
    }

    /**
     * @Route("/zapisz-zamowienie", name="order_save")
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();
        $order->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $repository->clear();

        if ($order->getDeliveryType()->getShortName() === 'PayU') {
            return $this->get('payment_payu')->createOrder($request, $order);
        } else {
            $this->get('order_mailer')->sendEmailToShop($order);
            $this->get('order_mailer')->sendEmailToCustomer($order);
        }

        return $this->redirectToRoute('order_confirmation', [], 303);
    }

    /**
     * @Route("/potwierdzenie-zamowienia", name="order_confirmation")
     * @return Response
     */
    public function confirmationAction()
    {
        return $this->render('OrderBundle:order:confirmation.html.twig');
    }

    /**
     * @Route("/payu", name="payu_notification")
     * @param Request $request
     * @return Response
     */
    public function payuNotificationAction(Request $request)
    {
        $notification = $this->get('payment_payu')->processNotification($request);
        if ($notification->isCompleted()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('OrderBundle:Order');
            $order = $repository->find($notification->getOrderId());

            $this->get('order_mailer')->sendEmailToShop($order);
            $this->get('order_mailer')->sendEmailToCustomer($order);
        }

        return new Response();
    }
}

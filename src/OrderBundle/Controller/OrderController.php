<?php

namespace OrderBundle\Controller;

use OrderBundle\Entity\Order;
use OrderBundle\Form\ShippingDetailsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @Route("/zloz-zamowienie", name="put_order")
     * @return Response
     */
    public function putOrderAction()
    {
        return $this->redirectToRoute('order_shipping_details');
    }

    /**
     * @Route("/zloz-zamowienie/dane-wysylki", name="order_shipping_details")
     * @param Request $request
     * @return Response
     */
    public function shippingDetailsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deliveryTypes = $em->getRepository('OrderBundle:DeliveryType')->getDeliveryTypes();

        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();

        $form = $this->createForm(ShippingDetailsType::class, $order, [
            'delivery_types' => $deliveryTypes,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $repository->persist($order);

            return $this->redirectToRoute('order_confirmation');
        }

        return $this->render('order/shipping_details.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/potwierdzenie-zamowienia", name="order_confirmation")
     * @return Response
     */
    public function confirmationAction()
    {
        $repository = $this->get('temporary_order_repository');
        $order = $repository->getOrder();

        return $this->render('order/confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}

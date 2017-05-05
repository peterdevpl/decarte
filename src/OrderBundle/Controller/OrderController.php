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
     * @return Response
     */
    public function shippingDetails(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deliveryTypes = $em->getRepository('OrderBundle:DeliveryType')->getDeliveryTypes();

        $order = new Order();

        $form = $this->createForm(ShippingDetailsType::class, $order, [
            'delivery_types' => $deliveryTypes,
        ]);

        return $this->render('order/shipping_details.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

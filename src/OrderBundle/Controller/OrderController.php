<?php

namespace OrderBundle\Controller;

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

    }
}

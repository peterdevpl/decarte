<?php

namespace Decarte\Shop\Controller\Order;

use Decarte\Shop\Entity\Order\Samples\Order;
use Decarte\Shop\Entity\Order\Samples\OrderItem;
use Decarte\Shop\Form\Order\OrderSamplesType;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SamplesOrderController extends Controller
{
    /**
     * @Route("/zamow-probki", name="shop_order_samples")
     * @param Request $request
     * @return Response
     */
    public function orderSamplesAction(
        Request $request,
        ProductTypeRepository $productTypeRepository,
        ProductRepository $productRepository
    ): Response {
        $order = new Order();
        for ($n = 0; $n < $this->getParameter('samples_count'); $n++) {
            $order->addItem(new OrderItem());
        }

        $productType = $productTypeRepository->find(1);
        $products = $productRepository->findDemos($productType);

        $form = $this->createForm(OrderSamplesType::class, $order, [
            'products' => $products,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $this->sendSamplesOrderEmailToShop($order);
            $this->sendSamplesOrderEmailToCustomer($order);

            return $this->redirectToRoute('shop_order_samples_confirmation');
        }

        return $this->render('samples/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/zamow-probki/potwierdzenie", name="shop_order_samples_confirmation")
     * @return Response
     */
    public function orderSamplesConfirmationAction(): Response
    {
        return $this->render('samples/confirmation.html.twig');
    }

    protected function sendSamplesOrderEmailToShop(Order $order)
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

        $this->get('mailer')->send($message);
    }

    protected function sendSamplesOrderEmailToCustomer(Order $order)
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

        $this->get('mailer')->send($message);
    }
}

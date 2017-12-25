<?php

namespace OrderBundle\Service;

use OrderBundle\Entity\Order;

class OrderMailer
{
    protected $mailer;
    protected $templating;
    protected $adminMail;

    public function __construct(\Swift_Mailer $mailer, $templating, string $adminMail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminMail = $adminMail;
    }

    public function sendEmailToShop(Order $order)
    {
        $express = ($order->getRealizationType()->getPrice() > 0 ? ' - EKSPRES!' : '');

        $message = \Swift_Message::newInstance()
            ->setSubject('ZAMÓWIENIE WWW' . $express)
            ->setTo($this->adminMail)
            ->setFrom([$this->adminMail => $order->getName()])
            ->setReplyTo($order->getEmail())
            ->setBody(
                $this->templating->render('order/mail/shop.html.twig', [
                    'order' => $order,
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendEmailToCustomer(Order $order)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Potwierdzenie przyjęcia zamówienia')
            ->setTo($order->getEmail())
            ->setFrom([$this->adminMail => 'Sklep ślubny decARTe.com.pl'])
            ->setReplyTo($this->adminMail)
            ->setBody(
                $this->templating->render('order/mail/customer.html.twig', [
                    'order' => $order,
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}

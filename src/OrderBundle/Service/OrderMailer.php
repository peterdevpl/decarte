<?php

namespace OrderBundle\Service;

use OrderBundle\Entity\Order;
use Symfony\Bundle\TwigBundle\TwigEngine;

class OrderMailer
{
    protected $mailer;
    protected $templating;
    protected $adminMail;
    protected $attachmentDir;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $templating, string $adminMail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminMail = $adminMail;
        $this->attachmentDir = realpath(__DIR__ . '/../Resources/attachments');
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
                $this->templating->render('@Order/order/mail/shop.html.twig', [
                    'order' => $order,
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendEmailToCustomer(Order $order)
    {
        $productTypes = $order->getProductTypes();

        $message = \Swift_Message::newInstance()
            ->setSubject('Potwierdzenie przyjęcia zamówienia')
            ->setTo($order->getEmail())
            ->setFrom([$this->adminMail => 'Sklep ślubny decARTe.com.pl'])
            ->setReplyTo($this->adminMail)
            ->setBody(
                $this->templating->render('@Order/order/mail/customer.html.twig', [
                    'order' => $order,
                    'formsCount' => count($productTypes),
                ]),
                'text/html'
            );

        foreach ($productTypes as $type) {
            $path = $this->attachmentDir . '/formularz-' . $type->getSlugName() . '.doc';
            $message->attach(\Swift_Attachment::fromPath($path));
        }

        $this->mailer->send($message);
    }
}

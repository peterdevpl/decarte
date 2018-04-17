<?php

namespace Decarte\Shop\Service;

use Decarte\Shop\Entity\Order\Order;
use Symfony\Component\Templating\EngineInterface;

class OrderMailer
{
    protected $mailer;
    protected $templating;
    protected $adminMail;
    protected $attachmentDir;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $templating,
        string $adminMail,
        string $attachmentDir
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminMail = $adminMail;
        $this->attachmentDir = $attachmentDir;
    }

    public function sendEmailToShop(Order $order)
    {
        $message = (new \Swift_Message())
            ->setSubject('ZAMÓWIENIE WWW' . $order->getRealizationType()->getShopEmailSuffix())
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
        $productTypes = $order->getProductTypes();

        $message = (new \Swift_Message())
            ->setSubject($order->getRealizationType()->getCustomerEmailPrefix() . 'Potwierdzenie przyjęcia zamówienia')
            ->setTo($order->getEmail())
            ->setFrom([$this->adminMail => 'Sklep ślubny decARTe.com.pl'])
            ->setReplyTo($this->adminMail)
            ->setBody(
                $this->templating->render('order/mail/customer.html.twig', [
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

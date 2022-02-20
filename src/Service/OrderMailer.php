<?php

declare(strict_types=1);

namespace Decarte\Shop\Service;

use Decarte\Shop\Entity\Order\Order;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class OrderMailer
{
    private $mailer;
    private $templating;
    private $adminMail;
    private $attachmentDir;

    public function __construct(
        MailerInterface $mailer,
        Environment $templating,
        string $adminMail,
        string $attachmentDir
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminMail = $adminMail;
        $this->attachmentDir = $attachmentDir;
    }

    public function sendEmailToShop(Order $order): void
    {
        $message = (new Email())
            ->subject('ZAMÓWIENIE WWW' . $order->getRealizationType()->getShopEmailSuffix())
            ->to($this->adminMail)
            ->from(new Address($this->adminMail, $order->getName()))
            ->replyTo($order->getEmail())
            ->html(
                $this->templating->render('order/mail/shop.html.twig', [
                    'order' => $order,
                ])
            );

        $this->mailer->send($message);
    }

    public function sendEmailToCustomer(Order $order): void
    {
        $productTypes = $order->getProductTypes();

        $message = (new Email())
            ->subject($order->getRealizationType()->getCustomerEmailPrefix() . 'Potwierdzenie przyjęcia zamówienia')
            ->to($order->getEmail())
            ->from(new Address($this->adminMail, 'Sklep ślubny decARTe.com.pl'))
            ->replyTo($this->adminMail)
            ->html(
                $this->templating->render('order/mail/customer.html.twig', [
                    'order' => $order,
                    'formsCount' => \count($productTypes),
                ])
            );

        foreach ($productTypes as $type) {
            $path = $this->attachmentDir . '/formularz-' . $type->getSlugName() . '.doc';
            $message->attachFromPath($path);
        }

        $this->mailer->send($message);
    }
}

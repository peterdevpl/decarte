<?php

declare(strict_types=1);

namespace Decarte\Shop\Service;

use Decarte\Shop\Entity\Order\Samples\Order;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class SamplesOrderMailer
{
    private $mailer;
    private $templating;
    private $adminMail;

    public function __construct(
        MailerInterface $mailer,
        Environment $templating,
        string $adminMail
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminMail = $adminMail;
    }

    public function sendSamplesOrderEmailToShop(Order $order): void
    {
        $message = (new Email())
            ->subject('ZAMÓWIENIE WWW - PRÓBKI')
            ->to($this->adminMail)
            ->from(new Address($this->adminMail, $order->getName()))
            ->replyTo($order->getEmail())
            ->html(
                $this->templating->render('samples/mail/shop.html.twig', [
                    'order' => $order,
                ])
            );

        $this->mailer->send($message);
    }

    public function sendSamplesOrderEmailToCustomer(Order $order): void
    {
        $message = (new Email())
            ->subject('Zamówienie decarte.com.pl - próbki')
            ->to($order->getEmail())
            ->from(new Address($this->adminMail, 'Sklep ślubny decARTe.com.pl'))
            ->replyTo($this->adminMail)
            ->html(
                $this->templating->render('samples/mail/customer.html.twig', [
                    'order' => $order,
                ])
            );

        $this->mailer->send($message);
    }
}

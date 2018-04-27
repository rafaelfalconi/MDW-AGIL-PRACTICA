<?php

namespace AppBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SendEmailsController
{
    private $mailer;
    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('twig');
    }

    public function reservationConfirmation($reservationId, $reservationPrice, $hotelEmail, $managerEmail, $customerEmail)
    {
        $message = (new \Swift_Message('Reserva registrada'))
            ->setFrom($hotelEmail)
            ->setTo(array($customerEmail,$managerEmail))
            ->setBody(
                $this->templating->render(
                    'emails/reservation-confirmation.html.twig',
                    array('reservationId' => $reservationId, 'reservationPrice' => $reservationPrice)
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    public function reservationPaymentInfo($reservationPrice, $reservationId, $hotelEmail, $customerEmail)
    {
        $message = (new \Swift_Message('Pago reserva'))
            ->setFrom($hotelEmail)
            ->setTo($customerEmail)
            ->setBody(
                $this->templating->render(
                    'emails/reservation-payment.html.twig',
                    array('reservationPrice' => $reservationPrice, 'reservationId' => $reservationId)
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

}
<?php
declare(strict_types=1);

namespace App\Shop\Application\Event\Product;

use App\Shop\Application\Event\EventHandlerInterface;
use App\Shop\Application\Product\Event\ProductWasCreatedEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendEmailWhenProductWasCreatedEventHandler implements MessageHandlerInterface, EventHandlerInterface
{
    public function __construct(private MailerInterface $mailer) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(ProductWasCreatedEvent $event)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }
}

<?php

namespace Effectra\Core\Authentication\EventListeners;

use Effectra\Core\Authentication\Events\UserLoggedEvent;
use Effectra\Core\Mail\AppMail;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Mail\Mail;
use Effectra\Router\Resolver;

/**
 * Class SendUserLoggedNtfMailListener
 *
 * Event listener for sending notification emails when a user logs in.
 */
class SendUserLoggedNtfMailListener
{
    /**
     * Handle the user logged event.
     *
     * @param UserLoggedEvent $event
     */
    public function __invoke(UserLoggedEvent $event): void
    {
        // Create a new mail instance
        $mail = new Mail();
        $mail
            ->from(AppMail::fromAddress())
            ->to($event->getUser()->getEmail())
            ->subject('User Logged')
            ->msg('User ' . $event->getUser()->getUsername() . ' logged in at ' . date(DATE_ATOM));

        // Resolve the mailer instance using the resolver
        $mailer = Resolver::resolveClass(MailerInterface::class);

        // Send the mail
        $mailer->send($mail);

        // Stop the propagation of the event
        $event->stopPropagation();
    }
}

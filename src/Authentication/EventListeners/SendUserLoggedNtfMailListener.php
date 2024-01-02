<?php

namespace Effectra\Core\Authentication\EventListeners;

use Effectra\Core\Authentication\Events\UserLoggedEvent;
use Effectra\Core\Mail\AppMail;
use Effectra\Mail\Components\Address;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Mail\Mail;
use Effectra\Router\Resolver;

class SendUserLoggedNtfMailListener
{
  public function __invoke(UserLoggedEvent $event): void
  {
    $mail =  new Mail();
    $mail
      ->from(AppMail::fromAddress())
      ->to($event->getUser()->getEmail())
      ->subject('User Logged')
      ->msg('user ' . $event->getUser()->getUsername() . ' logged at ' . date(DATE_ATOM));

    $mailer = Resolver::resolveClass(MailerInterface::class);

    $mailer->send($mail);
    $event->stopPropagation();
  }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Mail\Contracts\MailInterface;

/**
 * @method static \Effectra\Mail\Contracts\MailerInterface send(MailInterface $mail)
 * 
 */
class Mailer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailerInterface::class;
    }
}

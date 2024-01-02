<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Mail\AppMail;
use Effectra\Mail\Inbox;

class InboxProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Inbox::class, function () {

            $config = AppMail::inboxConfig();

            extract($config);

            $mailer = new Inbox(
                $driver,
                $host,
                intval($port),
                $username,
                $password
            );
            return $mailer;
        });
    }
}

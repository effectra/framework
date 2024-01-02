<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Mail\AppMail;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Mail\Services\PHPMailerService;

class MailerServiceProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(MailerInterface::class, function () {
            $config = AppMail::senderConfig();
            extract($config);
            $mailer = new PHPMailerService(
                $driver,
                $host,
                intval($port),
                $username,
                $password,
                $from
            );
            return $mailer;
        });
    }
}

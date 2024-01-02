<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Application;
use Effectra\Core\Authentication\Auth;
use Effectra\Core\Authentication\Contracts\AuthInterface;
use Effectra\Core\Authentication\Services\UserLoginCodeService;
use Effectra\Core\Authentication\UserProviderService;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Security\Token;
use Effectra\Session\Contracts\SessionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class AuthProvider extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(AuthInterface::class, function () {

            $provider = new UserProviderService();

            $session = Application::container()->get(SessionInterface::class);

            $token = Application::container()->get(Token::class);

            $mailer = Application::container()->get(MailerInterface::class);

            $userLoginCode = new UserLoginCodeService();

            $eventDispatcher = Application::container()->get(EventDispatcherInterface::class);
            
            return new Auth($provider, $session, $token,$mailer, $userLoginCode, $eventDispatcher);
        });
    }
}

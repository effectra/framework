<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Security\Csrf;
use Effectra\Session\Contracts\SessionInterface;

class CsrfProvider  extends ServiceProvider implements ServiceInterface
{
    public function __construct(protected SessionInterface $session) {
    }
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Csrf::class, function () {
            $csrf = new Csrf(
                $this->session
            );
            return $csrf;
        });
    }
}

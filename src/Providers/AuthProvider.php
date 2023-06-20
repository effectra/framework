<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Auth\Authentication;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;

class AuthProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Authentication::class,fn()=> new Authentication());
    }
}

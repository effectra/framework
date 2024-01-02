<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Application;
use Effectra\Core\Authentication\Contracts\RequestValidatorFactoryInterface;
use Effectra\Core\Authentication\Validation\RequestValidatorFactory;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;

class RequestValidatorFactoryProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(RequestValidatorFactoryInterface::class,fn()=> new RequestValidatorFactory(Application::container()));
    }
}

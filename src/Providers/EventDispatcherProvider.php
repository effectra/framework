<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\EventDispatcher\EventDispatcher;
use Effectra\EventDispatcher\ListenerProvider;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcherProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(EventDispatcherInterface::class,function ( ) {
            return new EventDispatcher(new ListenerProvider([]));
        });
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Cache\AppCache;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Psr\SimpleCache\CacheInterface;

class CacheInterfaceProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(CacheInterface::class,function(){
           return AppCache::getDriver();
        });
    }
}

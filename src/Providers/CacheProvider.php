<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Cache;
use Effectra\Core\Cache\AppCache;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;

class CacheProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Cache::class,function(){
           return AppCache::getDriver();
        });
    }
}

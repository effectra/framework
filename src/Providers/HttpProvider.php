<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Http\Factory\RequestFactory;
use Effectra\Core\Http\Factory\ResponseFactory;
use Effectra\Http\Factory\UriFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class HttpProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(ResponseFactoryInterface::class,fn()=> new ResponseFactory());
        $provider->bind(RequestFactoryInterface::class,fn()=> new RequestFactory());
        $provider->bind(UriFactoryInterface::class,fn()=> new UriFactory());
    }
}

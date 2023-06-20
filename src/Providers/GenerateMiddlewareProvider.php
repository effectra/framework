<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Generator\Creator;
use Effectra\Http\Server\GenerateMiddleware;

class GenerateMiddlewareProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(GenerateMiddleware::class ,fn () => new GenerateMiddleware(new Creator()));
    }
}

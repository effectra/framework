<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Log\Logger;

class LoggerProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Logger::class, function () {
            $path = Application::storagePath('logs/effectra.log');
            return new Logger($path);
        });
    }
}

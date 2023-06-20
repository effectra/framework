<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use App\AppCore;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;

class ApplicationProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Application::class, new Application(new AppCore()));
    }
}

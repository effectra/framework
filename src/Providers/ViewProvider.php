<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\View;
use Effectra\Renova\Reader;

class ViewProvider extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(View::class, function () {
            $v = new View(
                new Reader(),
                new ConfigFile(Application::configPath('view.php'))
            );
            return $v;
        });
    }
}

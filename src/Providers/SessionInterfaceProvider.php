<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Session\Contracts\SessionInterface;
use Effectra\Session\Session;

class SessionInterfaceProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(SessionInterface::class, function () {
            $configFile = new ConfigFile(Application::configPath('session.php'));
            $config = $configFile->read();

            extract($config);

            $s = new Session(
                name: $name,
                expiresOrOptions: $expire,
                path: $path,
                domain: $domain,
                secure: $secure,
                httponly: $httponly,
            );

            return $s;
        });
    }
}

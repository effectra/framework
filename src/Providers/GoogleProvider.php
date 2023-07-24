<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\ThirdParty\Google;



class GoogleProvider extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Google::class, function () {

            $configFile = new ConfigFile(Application::configPath('services.php'));
            $config = $configFile->getSection('google');

            extract($config);

            $google = new Google(
                $client_id,
                $client_secret,
                $redirect_url,
                []
            );

            return $google;
        });
    }
}

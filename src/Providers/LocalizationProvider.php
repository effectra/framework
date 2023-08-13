<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Localization;

class LocalizationProvider extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Localization::class, function () {

            $lang = Application::getLang();

            return new Localization($lang);
        });
    }
}

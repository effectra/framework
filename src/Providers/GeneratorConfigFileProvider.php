<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorConfigFile;

class GeneratorConfigFileProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(GeneratorConfigFile::class, function () {
            return new GeneratorConfigFile(new Creator(), new ConfigFile());
        });
    }
}

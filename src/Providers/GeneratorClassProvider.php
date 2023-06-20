<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class GeneratorClassProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(GeneratorClass::class, new GeneratorClass(new Creator()));
    }
}

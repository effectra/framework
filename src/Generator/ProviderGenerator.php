<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Application;
use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Fs\Path;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class ProviderGenerator implements GeneratorInterface
{
    public static function make(string $className,string $savePath,array $option = []):int|false
    {
        $namespace = $option['namespace'] ? 'App\Providers' . $option['namespace'] : 'App\Providers';
        $class = new GeneratorClass(new Creator(), $className);
        $content = '$provider->bind();';
        return $class
            ->withNameSpace($namespace)
            ->withPackages([
                'Effectra\Core\Contracts\ProviderInterface',
                'Effectra\Core\Contracts\ServiceInterface',
            ])
            ->withImplements('ServiceInterface')
            ->withMethod(
                name: 'register',
                content: $content,
                args:  [],
                return: 'void'
            )
            ->withArgument(
                methodName: 'register',
                name: 'provider',
                type: 'ProviderInterface'
            )
            ->generate()
            ->save($savePath);
    }
}

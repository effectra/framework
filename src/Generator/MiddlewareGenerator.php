<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class MiddlewareGenerator implements GeneratorInterface
{

    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $namespace = $option['namespace'] ? 'App\Middlewares' . $option['namespace'] : 'App\Middlewares';
        $class = new GeneratorClass(new Creator(), $className);
        $content = 'return $handler->handle($request);';

        return $class
            ->withExtends('Middleware')
            ->withNameSpace($namespace)
            ->withPackages([
                'Effectra\Http\Server\Middleware',
                'Psr\Http\Message\ResponseInterface',
                'Psr\Http\Message\ServerRequestInterface',
                'Psr\Http\Server\MiddlewareInterface',
                'Psr\Http\Server\RequestHandlerInterface'
            ])
            ->withImplements('MiddlewareInterface')
            ->withMethod(name: 'process', args: [], return: 'ResponseInterface', content: $content)
            ->withArgument('process', 'ServerRequestInterface', 'request')
            ->withArgument('process', 'RequestHandlerInterface', 'handler')
            ->generate()
            ->save($savePath);
    }
}

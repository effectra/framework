<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class ControllerGenerator implements GeneratorInterface
{

    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $class = new GeneratorClass(new Creator(), $className);

        $content = '
        return $response;
        ';
        return $class
            ->withNameSpace('App\Controllers')
            ->withPackages([
                'Effectra\Core\Request',
                'Effectra\Core\Response'
            ])
            ->withMethod(
                static: false,
                name: 'index',
                return: '',
                content: $content,
                args: []
            )
            ->withArgument('index', 'Request', 'request')
            ->withArgument('index', 'Response', 'response')
            ->withArgument('index', '', 'args')
            ->generate()
            ->save($savePath);
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class ControllerApiGenerator implements GeneratorInterface
{

    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $class = new GeneratorClass(new Creator(), $className);
        $args = [
            [
                'type' => 'Request',
                'name' => 'request',
                'defaultValue' => '--',
            ],
            [
                'type' => 'Response',
                'name' => 'response',
                'defaultValue' => '--',
            ],
            [
                'type' => '',
                'name' => 'args',
                'defaultValue' => '--',
            ],
        ];
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
                name: 'read',
                return: '',
                content: $content,
                args: $args
            )
            ->withMethod(
                static: false,
                name: 'readOne',
                return: '',
                content: $content,
                args: $args
            )
            ->withMethod(
                static: false,
                name: 'create',
                return: '',
                content: $content,
                args: $args
            )
            ->withMethod(
                static: false,
                name: 'update',
                return: '',
                content: $content,
                args: $args
            )
            ->withMethod(
                static: false,
                name: 'delete',
                return: '',
                content: $content,
                args: $args
            )
            ->withMethod(
                static: false,
                name: 'deleteAll',
                return: '',
                content: $content,
                args: $args
            )
            
            ->generate()
            ->save($savePath);
    }
}

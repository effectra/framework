<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class ModelGenerator implements GeneratorInterface
{

    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $namespace = isset($option['namespace']) ?  "App\Models\\" . $option['namespace'] : 'App\Models';

        $class = new GeneratorClass(new Creator(), $className);

        return $class
            ->withNameSpace($namespace)
            ->withPackages([
                'Effectra\Core\Database\Model',
                'Effectra\Core\Database\ModelBase',
            ])
            ->withExtends('Model')
            ->withTraits([
                'ModelBase'
            ])
            ->generate()
            ->save($savePath);
    }
}

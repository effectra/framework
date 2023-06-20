<?php declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class ModelGenerator implements GeneratorInterface
{

    public static function make(string $className,string $savePath,array $option = []):int|false
    {
        $class = new GeneratorClass(new Creator(), $className);

        return $class
            ->withNameSpace('App\Models')
            ->withPackages([
                'Effectra\Core\Database\ModelBase',
            ])
            ->withVariable(
                name: 'table',
                static: true,
                defaultValue: $option['tableName'],
                typeVar: ''
            )
            ->withTraits([
                'ModelBase'
            ])
            ->generate()
            ->save($savePath)
            ;
    }
}
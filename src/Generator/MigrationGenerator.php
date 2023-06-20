<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class MigrationGenerator implements GeneratorInterface
{
    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $class = new GeneratorClass(new Creator(), $className);


        $contentUp =  '
            Schema::' . $option['action'] . '("' . $option['tableName'] . '", function (Table $table) {
                $table->id();
                $table->timestamps();
            });
        ';
        if ($option['action'] !== 'create') {
            $contentUp =  '
            Schema::' . $option['action'] . '("' . $option['tableName'] . '", function (Table $table) {
                
            });
        ';
        }
        $contentDown =  '
        Schema::table("' . $option['tableName'] . '", function (Table $table) {
            $table->drop();
        });
        ';

        return  $class
            ->withNameSpace('App\Migrations')
            ->withPackages([
                'Effectra\Core\Database\Migration',
                'Effectra\Core\Database\Schema',
                'Effectra\SqlQuery\Table'
            ])
            ->withMethod(
                name: 'up',
                return: 'void',
                args: [],
                content: $contentUp
            )
            ->withMethod(
                name: 'down',
                return: 'void',
                args: [],
                content: $contentDown
            )
            ->withExtends('Migration')
            ->generate()
            ->save($savePath);
    }
}

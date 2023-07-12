<?php declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class CommandGenerator implements GeneratorInterface
{

    public static function make(string $className,string $savePath,array $option = []):int|false
    {
        $class = new GeneratorClass(new Creator(), $className);
        return $class
            ->setName($className)
            ->withNameSpace('App\Commands')
            ->withPackages([
                'Effectra\Core\Command'
            ])
            ->withExtends('Command')
            ->withMethod(
                typeFunction: 'public',
                name: 'configure',
                args: [],
                return: 'void',
            )
            ->withMethod(
                typeFunction: 'public',
                name: 'execute',
                args: [],
                return: 'int',
                content: "\treturn 0;"
            )
            ->withArgument('execute', '', 'input')
            ->withArgument('execute', '', 'output')
            ->generate()
            ->save($savePath);
    }
}
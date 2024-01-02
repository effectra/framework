<?php

declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;
use Effectra\Generator\Creator;
use Effectra\Generator\GeneratorClass;

class CommandGenerator implements GeneratorInterface
{

    public static function make(string $className, string $savePath, array $option = []): int|false
    {
        $namespace = $option['namespace'] ? 'App\Commands' . $option['namespace'] : 'App\Commands';
        $class = new GeneratorClass(new Creator(), $className);

        $name = strtolower($option['command_name']);
        $description = "this is description";

        $content  = sprintf('$this->setName("%s")->setDescription("%s");', $name, $description);

        return $class
            ->setName($className)
            ->withNameSpace($namespace)
            ->withPackages([
                'Effectra\Core\Console\Command',
                'Symfony\Component\Console\Input\InputInterface',
                'Symfony\Component\Console\Output\OutputInterface',
            ])
            ->withExtends('Command')
            
            ->withMethod(
                'public',
                false,
                'execute',
                [],
                'int',
                "\treturn 0;"
            )
            ->withArgument('execute', 'InputInterface', 'input')
            ->withArgument('execute', 'OutputInterface', 'output')
            ->withMethod(
                'public',
                false,
                'configure',
                [],
                'void',
                $content
            )
            ->generate()
            ->save($savePath);
    }
}

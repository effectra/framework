<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Console;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\File;
use Effectra\Fs\Path;

use Effectra\Generator\GeneratorClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateConsole extends Command
{

    protected function configure()
    {
        $this->setName('command:make')
            ->setDescription('Generate command class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate View File');

        $name = $input->getArgument('name');

        $file = Application::appPath('app' . Path::ds() . 'Commands' . Path::ds() . $name . '.php');

        $io->text('Generate: ' . $file);

        if (File::exists($file)) {
            $io->warring('File exists !');
            return 0;
        }

        $content = Application::get(GeneratorClass::class);

        $className = ucfirst($name) ;

        $state =  $content
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
            ->save($file);

        if ($state) {
            $io->success('File created successfully!');
        }
        if ($state === false) {
            $errorMessage = ' Failed to create the file. Please check the file path and ensure that you have the necessary permissions.';

            $io->errorMsg($errorMessage);
        }


        return 0;
    }
}

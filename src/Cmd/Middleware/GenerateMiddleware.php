<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Middleware;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\MiddlewareGenerator;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateMiddleware extends Command
{

    protected function configure()
    {
        $this->setName('middleware:make')
            ->setDescription('Generate Middleware class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Middleware');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Middleware File');

        $name = $input->getArgument('name');

        $path = Application::appPath('app' . Path::ds() . 'Middlewares');

        if (!str_contains('Middleware', $name)) {
            $name = trim($name) . 'Middleware';
        }

        $className = ucfirst($name);

        $savePath = $path . Path::ds() . $name  . '.php';

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        $state = MiddlewareGenerator::make($className,$savePath);

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

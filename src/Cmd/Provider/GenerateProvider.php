<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Provider;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\ProviderGenerator;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateProvider extends Command
{

    protected function configure()
    {
        $this->setName('provider:make')
            ->setDescription('Generate Provider class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Provider');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Provider File');

        $name = $input->getArgument('name');


        if (!str_contains('Provider', $name)) {
            $name = trim($name) . 'Provider';
        }

        $className = ucfirst($name);

        $savePath = Application::appPath('app' . Path::ds() . 'Providers' . Path::ds() . $className . '.php');

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warring('File exists !');
            return 0;
        }


        $state = ProviderGenerator::make($className, $savePath);

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

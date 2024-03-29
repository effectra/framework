<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Controller;

use Effectra\Core\Application;
use Effectra\Core\ConfigureFile;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\ControllerApiGenerator;
use Effectra\Core\Generator\ControllerGenerator;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Core\Router\RouterConfigurator;
use Effectra\Fs\Directory;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateController extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('controller:make')
            ->setDescription('Generate Controller class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Controller')
            ->addOption('crud', 'crud', InputOption::VALUE_NONE, 'generate controller with crud methods')
            ->addOption('api', 'api', InputOption::VALUE_NONE, 'generate controller with api crud methods')
            ->addOption('route', 'r', InputOption::VALUE_NONE, 'add route with this controller');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Controller File');

        $name = $input->getArgument('name');

        $path = Application::appPath('app' . Path::ds() . 'Controllers');

        $config = new ConfigureFile('Controller', $name, $path);

        $className =  $config->toClassName($name);

        $savePath = $config->toFilePath(ConfigureFile::CREATE_FOLDER_IF_NOT_EXCITE);

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        $option = [
            'namespace' => $config->getNameSpace()
        ];

        $place = 'web';

        if ($input->getOption('api')) {
            $place = 'api';
            $state = ControllerApiGenerator::make($className, $savePath, $option);
        } else {
            $state = ControllerGenerator::make($className, $savePath, $option);
        }


        if ($input->getOption('route')) {
            RouterConfigurator::addRoute($className, $place);
        }

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

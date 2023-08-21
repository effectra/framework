<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Model;

use Bmt\PluralConverter\PluralConverter;
use Effectra\Core\Application;
use Effectra\Core\Console\AppConsole;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\ModelGenerator;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateModel extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('model:make')
            ->setDescription('Generate model class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model')
            ->addOption('migration', 'm', InputOption::VALUE_NONE, 'Generate migration for this model')
            ->addOption('controller', 'c', InputOption::VALUE_NONE, 'Generate controller for this model')
            ->addOption('api', 'api', InputOption::VALUE_NONE, 'generate controller with api crud methods')
            ->addOption('route', 'r', InputOption::VALUE_NONE, 'add route with this controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $plural = new PluralConverter();

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Model File');

        $name = $input->getArgument('name');

        $className = ucfirst($name);

        $tableName = strtolower($plural->convertToPlural($name));

        $path = Application::appPath('app' . Path::ds() . 'Models');

        $savePath = $path . Path::ds() . $className . '.php';

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        $state = ModelGenerator::make($className, $savePath, ['tableName' => $tableName]);

        if ($state) {
            $io->success('File created successfully!');
        }
        if ($state === false) {
            $errorMessage = ' Failed to create the file. Please check the file path and ensure that you have the necessary permissions.';

            $io->errorMsg($errorMessage);
        }

        if ($input->getOption('migration')) {
            AppConsole::print('php aval migration:make create_table_' . $tableName . ' --table=' . $tableName);
        }
        if ($input->getOption('controller')) {
            $controller = ucfirst($name);
            $option_controller = "";
            if ($input->getOption('api')) {
                $option_controller .= " --api ";
            }
            if ($input->getOption('r')) {
                $option_controller .= " --route ";
            }
            AppConsole::print("php aval controller:make $controller $option_controller");
        }

        return 0;
    }
}

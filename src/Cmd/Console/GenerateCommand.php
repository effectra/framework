<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Console;

use Effectra\Core\Application;
use Effectra\Core\ConfigureFile;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\CommandGenerator;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\File;
use Effectra\Fs\Path;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCommand extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('command:make')
            ->setDescription('Generate command class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Command');

        $name = $input->getArgument('name');

        $path = Application::appPath('app' . Path::ds() . 'Commands');

        $config = new ConfigureFile('Command', $name, $path);

        $className =  $config->toClassName($name);

        $savePath = $config->toFilePath(ConfigureFile::CREATE_FOLDER_IF_NOT_EXCITE);

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        $option = [
            'namespace' => $config->getNameSpace(),
            'command_name' => $name
        ];

        $state = CommandGenerator::make($className, $savePath, $option);

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

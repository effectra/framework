<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Config;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Effectra\Generator\GeneratorConfigFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateConfigFile extends Command
{

    protected function configure()
    {
        $this->setName('config:make')
            ->setDescription('Generate config file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the config file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Config File');

        $name = $input->getArgument('name');

        $path = Application::configPath();

        $savePath =  Path::format($path . Path::ds() .  $name) . '.php';

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        /** @var GeneratorConfigFile $g */
        $g = Application::container()->get(GeneratorConfigFile::class);

        $state = $g->generate()->save($savePath);


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

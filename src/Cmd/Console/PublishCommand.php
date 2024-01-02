<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Console;

use Effectra\Core\Application;
use Effectra\Core\ConfigureFile;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\Path;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PublishCommand extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('command:publish')
            ->setDescription('Publish command class in your app  cli commands')
            ->addArgument('name', InputArgument::REQUIRED, 'The ClassName of the command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Publish Command');

        $name = $input->getArgument('name');

        $path = Application::appPath('app' . Path::ds() . 'Commands');

        $config = new ConfigureFile('', $name, $path);

        $className =  $config->toClassName($name);

        if (class_exists($className)) {
            $io->warning('ClassName not exists !');
            return 0;
        }

        $file = Application::configPath('console.php');
        $configFile = file_get_contents($file);

        $newConfigFile =  str_replace("]\r\n", "\t\t\App\Commands\\" . $className . "::class,\r\n]\r\n", $configFile);

        $state = file_put_contents($file, $newConfigFile);

        if ($state) {
            $io->success('Command published successfully!');
        }
        if ($state === false) {
            $errorMessage = ' Failed to publish the command. Please check the file path and ensure that you have the necessary permissions.';

            $io->errorMsg($errorMessage);
        }


        return 0;
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Config;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteConfigFile extends Command
{

    protected function configure()
    {
        $this->setName('config:delete')
            ->setDescription('Delete config file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the config file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Delete File');

        $name = $input->getArgument('name');

        $file = Application::configPath( $name . '.php');

        $io->text('Delete: '.$file);

        if(!File::exists($file)){
            $io->warring('File does not exists !');
            return 0;
        }

        $state = File::delete($file);

        if ($state) {
            $io->success('File deleted successfully!');
        }
        if ($state === false) {
            $errorMessage = 'Failed to delete the file.';
            
            $io->errorMsg($errorMessage);
        }


        return 0;
    }
}

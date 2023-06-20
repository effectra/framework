<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Provider;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteProvider extends Command
{

    protected function configure()
    {
        $this->setName('provider:delete')
            ->setDescription('Delete provider class')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Provider class');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Delete Command Class File');

        $name = $input->getArgument('name');

        $file = Application::appPath('app'. Path::ds() .'Providers'. Path::ds() . $name . '.php');

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

<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\View;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeleteView extends Command
{

    protected function configure()
    {
        $this->setName('view:delete')
            ->setDescription('Delete view file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the view');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Delete View File');

        $name = $input->getArgument('name');

        $file = Application::viewPath($name . '.php');

        $io->text('Delete: '.$file);

        if(!File::exists($file)){
            $io->warning('File does not exists !');
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

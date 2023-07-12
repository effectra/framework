<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Fs\Directory;
use Effectra\Fs\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearMigrations extends Command
{

    protected function configure()
    {
        $this->setName('migration:clear')
             ->setDescription('Delete files of migration dirictory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Delete Command Class File');


        $file = Application::databasePath('migrations');

        $io->text('Delete dirictory files: '.$file);

        if(!File::exists($file)){
            $io->warning('File does not exists !');
            return 0;
        }

        $state = Directory::deleteFiles($file);

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

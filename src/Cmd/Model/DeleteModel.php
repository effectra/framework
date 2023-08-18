<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Model;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeleteModel extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('model:delete')
            ->setDescription('Delete model class')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model class')
            ->addOption('migration', 'm', InputOption::VALUE_NONE, 'Generate migration for this model')
            ->addOption('controller', 'c', InputOption::VALUE_NONE, 'Generate controller for this model');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Delete Command Class File');

        $name = $input->getArgument('name');

        $file = Application::appPath('app'. Path::ds() .'Models'. Path::ds() . $name . '.php');

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

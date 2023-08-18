<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\View;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateView extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('view:make')
            ->setDescription('Generate view file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the view')
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'The port number to use', 8080);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        function addDotsEverySecond($string)
        {
            $output = '';
            for ($i = 0; $i < 3; $i++) {
                $output .= $string . '...';
                echo $output . PHP_EOL;
                sleep(1);

                // Check if stop condition is met
                if ($i === 1) {
                    break;
                }
            }
        }

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate View File');


        $name = $input->getArgument('name');

        $file = Application::viewPath($name . '.php');

        $io->text('Generate: '.$file);

        if(File::exists($file)){
            $io->warning('File exists !');
            return 0;
        }
        addDotsEverySecond("");
        $content = sprintf('<h1>%s</h1>',$name) ;

        $state = File::put($file, $content);

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

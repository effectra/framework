<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Log;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\AppLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearLog extends Command
{

    protected function configure()
    {
        $this->setName('log:clear')
            ->setDescription('Clear application log file');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);
        
        $result = AppLogger::clear();

        if($result){
            $io->success("Log file cleared successfully");
        }else{
            $io->errorMsg("Log file cleared failed ");
        }

        return 0;
    }
}

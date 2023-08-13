<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Application;
use Effectra\Core\Console\AppConsole;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Database\AppDatabase;
use Effectra\Core\Database\Migration;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Migrate extends Command
{


   
    protected function configure()
    {
        $this->setName('migrate')
            ->setDescription('Run migrations');
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Run Migrations');

        $m = new Migration();

        $event = function ($fileName,$io){
           $io->addDots($fileName,50,"DONE\n");
        };

        $m->applyMigrations('up',$event);

        foreach ($m->appliedMigrations() as $migration) {
           echo $io->addDots($migration,"DONE\n");
        }

        return 0;
    }
}

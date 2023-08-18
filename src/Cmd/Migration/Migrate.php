<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Database\Migration;
use Effectra\Core\Log\ConsoleLogTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Migrate extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('migrate')
            ->setDescription('Run migrations')
            ->addArgument('file', InputArgument::OPTIONAL, 'The name of the migration file')
            ->addOption('down', 'd', InputOption::VALUE_NONE, 'migrate down');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Run Migrations');

        $m = new Migration();

        $event = function ($fileName, $io) {
            $io->addDots($fileName, 50, "DONE\n");
        };

        $act = 'up';

        if ($input->getOption('down')) {
            $act = 'down';
        }


        $name = $input->getArgument('file');

        if($name){
            if($m->isMigrated($name,$act)){
                echo "  This migration has been migrated\n";
            }else{
                $m->migrateWithLog($name,$act);
            }
            return 0;
        }else{
            $m->applyMigrations($act, $event);
        }


        if (empty($m->appliedMigrations())) {
            echo "  No migrations applied\n";
        }

        foreach ($m->appliedMigrations() as $migration) {
            echo $io->addDots($migration, "DONE\n");
        }

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Database;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Database\AppDatabase;
use Effectra\Core\Log\ConsoleLogTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabase extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('db:create')
            ->setDescription('Create database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Create database');

        $state = AppDatabase::create();

        if ($state) {
            $io->success('database created successfully!');
        }
        if ($state === false) {
            $errorMessage = 'Failed creating the database.';

            $io->errorMsg($errorMessage);
        }

        return 0;
    }
}

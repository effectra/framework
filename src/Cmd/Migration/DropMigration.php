<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Database\Migration;
use Effectra\Core\Log\ConsoleLogTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DropMigration extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('migration:drop')
            ->setDescription('Drop migrations table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Drop Migrations');

        $m = new Migration();

        $result = $m->dropMigration();

        if (!$result) {
            $io->errorMsg('failed drops table migrations');
        }
        $io->success('table dropped successfully!');

        return 0;
    }
}

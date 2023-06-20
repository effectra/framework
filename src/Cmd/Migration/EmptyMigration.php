<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Database\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmptyMigration extends Command
{

    protected function configure()
    {
        $this->setName('migration:empty')
            ->setDescription('Truncate migrations table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Truncate Migrations');

        $m = new Migration();

        $result = $m->emptyMigration();

        if (!$result) {
            $io->errorMsg('failed truncate table migrations');
        }
        $io->success('table truncated successfully!');

        return 0;
    }
}

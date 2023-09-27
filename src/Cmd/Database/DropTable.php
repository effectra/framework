<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Database;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Facades\DB;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\SqlQuery\Query;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DropTable extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('db:drop')
            ->setDescription('Drop database table')
            ->addArgument('table', InputArgument::REQUIRED, 'The name of the table you want dropped');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Drop database table');

        $name = $input->getArgument('table');

        $io->text('Drop table ' . $name);

        $query = Query::drop()->table($name)->dropTable();

        $state = DB::query((string) $query)->run();

        if ($state) {
            $io->success('Table dropped successfully!');
        }
        if ($state === false) {
            $errorMessage = 'Failed dropping the table';
            
            $io->errorMsg($errorMessage);
        }

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Exceptions\StructureException;
use Effectra\Core\Structure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Scan extends Command
{

    protected function configure()
    {
        $this->setName('scan')->setDescription('Scan folder structure of application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Scan Application');

        $s = new Structure();

        try {
            $s->scan();
        } catch (StructureException $e) {
            $io->errorMsg($e->getMessage());
        }

        return 0;
    }
}

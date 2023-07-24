<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Test;

use Effectra\Core\Test\AppTest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTest extends Command
{

    protected function configure()
    {
        $this->setName('test:run')
            ->setDescription('Runt Unit Test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        AppTest::run('');

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Test;

use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Core\Test\AppTest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTest extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('test:run')
            ->setDescription('Run Unit Test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        AppTest::run('');

        return 0;
    }
}

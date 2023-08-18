<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Key;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Facades\Token;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Core\Utils\EnvManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKey extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('key:generate')
            ->setDescription('Generate new Key');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Key');

        $key = Token::generateToken(30);

        $file = Application::appPath('.env');

        $env = new EnvManager($file);

        $env->set('APP_KEY', $key);

        if ($env->save()) {
            echo "  KEY:  $key\n";
        }else{
            echo "  Key generate failed\n";
        }

        return 0;
    }
}

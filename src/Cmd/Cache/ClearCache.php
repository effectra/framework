<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Cache;

use Effectra\Core\Cache\AppCache;
use Effectra\Core\Console\ConsoleBlock;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCache extends Command
{

    protected function configure()
    {
        $this->setName('cache:clear')
            ->setDescription('Delete all cache stored in folder "storage/cache/" ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new ConsoleBlock($input, $output);

        $action = AppCache::clear();

        if($action){
            $io->success('App cache cleared successfully!');
        }else{
            $io->errorMsg("Unable to delete app cache");
        }

        return 0;
    }
}

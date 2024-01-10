<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Tasks;

use Effectra\Core\Application;
use Effectra\Core\ConfigureFile;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Core\Tasks\CronScheduler;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AddTask extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('task:add')
            ->setDescription('Add task to cron job')
            ->addArgument('name', InputArgument::REQUIRED, 'The script filename')
            ->addOption('m', null, InputOption::VALUE_OPTIONAL, 'add to cron command', '*')
            ->addOption('h', null, InputOption::VALUE_OPTIONAL, 'add to cron command', '*')
            ->addOption('d', null, InputOption::VALUE_OPTIONAL, 'add to cron command', '*')
            ->addOption('mth', null, InputOption::VALUE_OPTIONAL, 'add to cron command', '*')
            ->addOption('w', null, InputOption::VALUE_OPTIONAL, 'add to cron command', '*');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Provider File');

        $cron = new CronScheduler();

        $name =  $input->getArgument('name');

        $path = Application::appPath('scripts' . Path::ds() . $name . '.php');

        $config = new ConfigureFile('', $name, $path);

        $savePath = $config->toFilePath(ConfigureFile::CREATE_FOLDER_IF_NOT_EXCITE);

        $cron->addJob(
            $input->getOption('m'),
            $input->getOption('h'),
            $input->getOption('d'),
            $input->getOption('mth'),
            $input->getOption('w'),
            $savePath,
        );

        $cron->save();

        return 0;
    }
}

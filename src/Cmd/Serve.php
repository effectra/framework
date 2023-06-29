<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd;

use Effectra\Core\Application;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Core\Server\AppServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The Serve command starts a PHP development server on localhost:8080
 * with the public/ directory as the document root.
 */
class Serve extends Command
{
    use ConsoleLogTrait;
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected static $defaultName = 'serve';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected static $defaultDescription = 'Start the development server';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'The port number to use', 8080);
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get the port number from the command line options
        $port = $input->getOption('port');

        // Determine the path to the public directory
        $publicDir = Application::publicPath();

        // Check if the public directory exists
        if (!is_dir($publicDir)) {
            $output->writeln('<fg=red>ERROR:</> Public directory not found.');
            return Command::FAILURE;
        }

        // Start the development server
        $output->writeln("<fg=green>Server started:</> <fg=yellow>http://localhost:{$port}</>");
        $output->writeln("<fg=green>Document root:</> <fg=yellow>{$publicDir}</>");
        
        return AppServer::run($port);

        return Command::SUCCESS;
    }
}

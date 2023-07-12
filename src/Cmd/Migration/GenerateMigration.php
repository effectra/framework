<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Migration;

use Effectra\Core\Application;
use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Generator\MigrationGenerator;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateMigration extends Command
{

    protected function configure()
    {
        $this->setName('migration:make')
            ->setDescription('Generate migration class file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'The table name', 'table_name')
            ->addOption('action', 'a', InputOption::VALUE_OPTIONAL, 'The action you want do with migration like "create,update"', 'create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new ConsoleBlock($input, $output);

        $io->info('Generate Migration File');

        $name = $input->getArgument('name');

        $tableName = $input->getOption('table');

        $action = $input->getOption('action') === 'create' ? $input->getOption('action') : 'table';

        $className = 'Migrations_' . time() . '_' . strtolower($name);

        $path = Application::databasePath('migrations');

        $savePath = $path . Path::ds() .  $className . '.php';

        $io->text('Generate: ' . $savePath);

        if (File::exists($savePath)) {
            $io->warning('File exists !');
            return 0;
        }

        $state = MigrationGenerator::make($className, $savePath, [
            'tableName' => $tableName,
            'action' => $action,
        ]);

        if ($state) {
            $io->success('File created successfully!');
        }
        if ($state === false) {
            $errorMessage = ' Failed to create the file. Please check the file path and ensure that you have the necessary permissions.';

            $io->errorMsg($errorMessage);
        }


        return 0;
    }
}

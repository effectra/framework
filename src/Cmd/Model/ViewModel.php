<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Model;

use Effectra\Core\Console\ConsoleBlock;
use Effectra\Core\Log\ConsoleLogTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ViewModel extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('model:display')
            ->setDescription('Delete model class')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model class')
            ->addOption('size', 's', InputArgument::OPTIONAL, 'set size of data returned');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $io = new ConsoleBlock($input, $output);

        $io->info('View Model');

        $model = $input->getArgument('name');

        $data =  [];

        if (class_exists($model)) {
            $table = new Table($output);
            if ($input->getOption('size')) {
                $size = (int) $input->getOption('size');
                $data = $model::limit($size);
            } else {
                $data = $model::all();
            }

            if (!$data->isEmpty()) {
                $table->setHeaders(array_keys($data->first()->toArray()));

                foreach ($data as $row) {
                    $table->addRow(array_values($row->toArray()));
                }
            } else {
                $io->warning('No Data in Model ' . $model);
            }

            $table->render();
        } else {
            $io->errorMsg('Model ' . $model . ' not exists');
        }

        return 0;
    }
}

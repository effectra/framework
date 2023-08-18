<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\View;

use Effectra\Core\Application;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\Directory;
use Effectra\Fs\Path;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class Views extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('view:display')
            ->setDescription('Display a all view files in the view folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(),__FILE__);

        $table = new Table($output);

        $path = Application::viewPath();

        $files = Directory::files($path);

        $table->setHeaders(['View', 'Path']);

        // Add rows to the table
        foreach ($files as $file) {
            $filename = Path::removeExtension($file);
            $table->addRow([$filename,'view/'. $file]);
        }

        // Render the table
        $table->render();

        return 0;
    }
}

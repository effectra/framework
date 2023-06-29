<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Router;

use Effectra\Core\Application;
use Effectra\Fs\File;
use Effectra\Router\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class Routes extends Command
{

    protected function configure()
    {
        $this->setName('route:display')
            ->setDescription('Display a table in the console');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $router = router();

        $webFile =  Application::routesPath('web.php');
        $apiFile =  Application::routesPath('api.php');
       
        if (File::exists($apiFile)) {
            $api = require $apiFile;
            $api($router);
        }

        $router->setPreRoute('api//');

         if (File::exists($webFile)) {
            $web = require $webFile;
            $web($router);
        }

        // Set the table headers
        $table->setHeaders(['Route Path','HTTP Method','Name','Controller','Controller Method','Middleware']);

        // Add rows to the table
        foreach ($router->routes() as $route) {
            $table->addRow([$route['pre_pattern'].$route['pattern'],$route['method'],$route['name'], $route['controller'],$route['controller_method'],join("\n",$route['middleware'])]);
        }

        // Render the table
        $table->render();

        return 0;
    }
}

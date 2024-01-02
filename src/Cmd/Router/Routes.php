<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Router;

use Closure;
use Effectra\Core\Application;
use Effectra\Core\Log\ConsoleLogTrait;
use Effectra\Fs\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class Routes extends Command
{
    use ConsoleLogTrait;

    protected function configure()
    {
        $this->setName('route:display')
            ->setDescription('Display a table in the console');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log($this->getName(), __FILE__);

        $table = new Table($output);

        $router = router();

        $webFile =  Application::routesPath('web.php');
        $apiFile =  Application::routesPath('api.php');

        if (File::exists($apiFile)) {
            $api = require $apiFile;
            $api($router);
        }

        $router->setPreRoute('api');

        if (File::exists($webFile)) {
            $web = require $webFile;
            $web($router);
        }

        // Set the table headers
        $table->setHeaders(['Route Path', 'HTTP Method', 'Name', 'Controller', 'Controller Method', 'Middleware']);

        // Add rows to the table
        foreach ($router->routes() as $route) {
            $url = $route['pre_pattern'] !==  '' ? ($route['pre_pattern'] . '/'  . $route['pattern']) : $route['pattern'];
            $table->addRow(
                [
                    str_replace('//', '/', $url),
                    $route['method'], $route['name'],
                    $this->getControllerName($route['controller']),
                    $route['controller_method'],
                    $this->getMiddlewaresName($route['middleware'])
                ]
            );
        }

        // Render the table
        $table->render();

        return 0;
    }

    public function getMiddlewaresName($middlewares)
    {
        $names = array_map(function ($m) {
            $name_arr = explode('\\', $m);
           return end($name_arr);
        }, $middlewares);

        return join("\n", $names);
    }

    public function getControllerName($controller)
    {
        if(is_string($controller)){
            $name_arr = explode('\\', $controller);
            return end($name_arr);
        }
        return ':Closure';
    }
}

<?php

namespace Effectra\Core\Router;

use Effectra\Core\Application;
use Effectra\Fs\File;

class RouterConfigurator
{
    /**
     * Add a new route to a specified route configuration file.
     *
     * @param string $controller The parsed route controller to be added.
     * @param string $file The name of the route configuration file (without the extension).
     * @return bool Returns true if the route was added successfully, false otherwise.
     * @throws \Exception When the specified route configuration file does not exist.
     */
    public static function addRoute(string $controller, string $file, ?string $path = null): bool
    {
        $filePath = Application::routesPath($file . '.php');

        if (!File::exists($filePath)) {
            throw new \Exception("File not found: $filePath");
        }

        $name = strtolower(str_replace('Controller', '', $controller));

        $pack = "use App\\Controllers\\$controller";

        $pathRoute =  $path ? $path : $name;

        $routeParsed = '$router->crud("/' . $pathRoute . '",' . $controller . '::class,"read|readone|create|update|delete|deleteAll")';

        $content = File::getContent($filePath);


        $newContent = str_replace("\nreturn function", "$pack;\n\nreturn function", $content);

        $newContent = str_replace('};', "\n\t$routeParsed;\n};", $newContent);

        return (bool) File::put($filePath, $newContent);
    }
}

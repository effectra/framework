<?php

namespace Effectra\Core\View;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;

class AppView
{
    /**
     * Get the cache configuration.
     *
     * @return array The cache configuration.
     */
    public static function getConfig(): array
    {
        $file = Application::configPath('view.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    /**
     * get path of encore folder
     *
     * @return string
     */
    public static function getEncoreFolderPath():string
    {
        return static::getConfig()['encore_dir'] ?? Application::publicPath('static');
    }
}

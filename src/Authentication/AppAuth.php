<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;

class AppAuth
{
    /**
     * Get the database configuration.
     *
     * @return array The database configuration.
     */
    private static function getConfig()
    {
        $file = Application::configPath('auth.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }
}

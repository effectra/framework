<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;

/**
 * Class AppAuth
 *
 * Helper class for authentication-related configuration in the application.
 */
class AppAuth
{
    /**
     * Get the database configuration.
     *
     * @return array The database configuration.
     */
    private static function getConfig()
    {
        // Path to the auth configuration file
        $file = Application::configPath('auth.php');

        // Read the configuration from the file
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }
}

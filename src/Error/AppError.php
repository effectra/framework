<?php

declare(strict_types=1);

namespace Effectra\Core\Error;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Exceptions\WoopsException;

/**
 * The AppError class handles application-level errors and exceptions.
 */
class AppError
{

    /**
     * Get the error configuration from the app configuration file.
     *
     * @return array The error configuration.
     */
    private static function getConfig(): array
    {
        $file = Application::configPath('app.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->getSection('errors');

        return $config;
    }


    /**
     * Handle errors based on the specified type.
     *
     * @param string $type The error handling type ('web', 'api', or 'cli').
     * @return void
     */
    public static function handler($type = 'web')
    {
        if ($type === 'web') {
            WoopsException::handle();
        } elseif ($type === 'api') {
            $apiError = new ApiError();
            $apiError->register();
        } elseif ($type !== 'cli') {
            return ;
        }

        $config = static::getConfig();

        if (!$config['display']) {
            error_reporting(0);
            ini_set('display_errors', '0');
            $logger = new ErrorLogger();
            $logger->register();
        }
    }
}

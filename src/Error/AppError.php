<?php

declare(strict_types=1);

namespace Effectra\Core\Error;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Exceptions\WoopsException;
use Psr\Http\Message\RequestInterface;

/**
 * The AppError class handles application-level errors and exceptions.
 */
class AppError
{
    protected static $endpoint;

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
     * Determine the endpoint (web or API) based on the request.
     *
     * @param RequestInterface $request The HTTP request.
     * @return string The endpoint type ('web' or 'api').
     */
    public static function endpoint(RequestInterface $request): string
    {
        return Application::isApiPath($request) ? 'api' : 'web';
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
            return;
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

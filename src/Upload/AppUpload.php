<?php 

declare(strict_types=1);

namespace Effectra\Core\Upload;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;

/**
 * Class AppUpload
 *
 * Handles the upload configuration and provides access to configuration parameters.
 */
class AppUpload
{
    /**
     * Get the upload configuration.
     *
     * @return array The upload configuration.
     */
    private static function getConfig(): array
    {
        $file = Application::configPath('storage.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->getSection('upload');
        return $config;
    }

    /**
     * Get the default driver configuration as an object.
     *
     * @return object The default driver configuration as an object.
     */
    public static function getDriver(): object
    {
        $config = static::getConfig();

        return (object) match ($config['default_driver']) {
            'local' => $config['driver']['local']
        };
    }

    /**
     * Get the allowed file types for uploading.
     *
     * @return array The allowed file types for uploading.
     */
    public static function getTypes(): array
    {
        return static::getConfig()['types'] ?? [];
    }

    /**
     * Get the maximum allowed file size for uploading.
     *
     * @return mixed|null The maximum allowed file size for uploading, or null if not specified in the configuration.
     */
    public static function getMaxSize()
    {
        return static::getConfig()['max_size'] ?? null;
    }
}

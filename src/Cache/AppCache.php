<?php

declare(strict_types=1);

namespace Effectra\Core\Cache;

use Effectra\Cache\Psr16\FileCache;
use Effectra\Cache\Psr16\JsonCache;
use Effectra\Cache\Psr16\RedisCache;
use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Fs\Directory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * The AppCache class provides methods for caching operations.
 */
class AppCache
{
    /**
     * Get the cache configuration.
     *
     * @return array The cache configuration.
     */
    public static function getConfig(): array
    {
        $file = Application::configPath('cache.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    /**
     * Get the cache directory path.
     *
     * @return string The cache directory path.
     */
    public static function cacheDirectory(): string
    {
        return Application::storagePath('cache');
    }

    /**
     * Get the cache driver based on the configuration.
     *
     * @return CacheInterface|CacheItemPoolInterface|null The cache driver instance.
     * @throws \Exception If there is an error processing the driver.
     */
    public static function getDriver(): CacheInterface|CacheItemPoolInterface|null
    {
        $path = static::cacheDirectory();

        $config = static::getConfig();

        $defaultDriver = $config['default_driver'];

        $driver = $config['driver'][$defaultDriver];

        if (!isset($driver)) {
            throw new \Exception("Error Processing Driver");
        }

        if ($driver === 'file_storage') {
            return new FileCache($path);
        }

        if ($driver === 'json') {
            return new JsonCache($path);
        }

        if ($driver === 'redis') {
            return new RedisCache();
        }

        return null;
    }

    /**
     * Clear the cache by deleting all cache files.
     *
     * @return bool True if the cache is cleared successfully, false otherwise.
     */
    public static function clear(): bool
    {
        $path = static::cacheDirectory();
        return Directory::deleteFiles($path);
    }
}

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

class AppCache
{

    /**
     * Get the cache configuration.
     *
     * @return array The cache configuration.
     */
    public static function getConfig()
    {
        $file = Application::configPath('cache.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    public static function  cacheDirectory(): string
    {
        return Application::storagePath('cache');
    }

    public static function getDriver(): CacheInterface|CacheItemPoolInterface|null
    {
        $path = static::cacheDirectory();

        $config = static::getConfig();

        $default_driver = $config['default_driver'];

        $driver = $config['driver'][$default_driver];

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

    public static function clear(): bool
    {
        $path = static::cacheDirectory();
        return Directory::deleteFiles($path);
    }
}

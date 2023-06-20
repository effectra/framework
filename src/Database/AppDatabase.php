<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Facades\DB;
use Effectra\Fs\File;
use Effectra\SqlQuery\Query;
use PDOException;

class AppDatabase
{
    /**
     * Get the database configuration.
     *
     * @return array The database configuration.
     */
    private static function getConfig()
    {
        $file = Application::configPath('database.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    /**
     * Get the driver configuration for the specified name.
     *
     * @param string $name The name of the driver.
     * @return array|null The driver configuration or null if not found.
     */
    private static function getDriver(string $name): array|null
    {
        return static::getConfig()['driver'][$name] ?? null;
    }

    /**
     * Get the default driver.
     *
     * @return string|null The default driver name or null if not found.
     */
    public static function getDriverDefault(): string|null
    {
        return static::getConfig()['default'] ?? null;
    }

    /**
     * Create a MySQL database.
     *
     * @return bool True if the database was successfully created, false otherwise.
     */
    public static function makeDatabaseMySql(): bool
    {
        $driver = static::getDriver('mysql');
        if ($driver) {
            $name = $driver['database'];
            $query = Query::createDatabase($name);
            try {
                return DB::withQuery($query)->run();
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Create a SQLite database.
     *
     * @return bool True if the database was successfully created, false otherwise.
     */
    public static function makeDatabaseSqlite(): bool
    {
        $driver = static::getDriver('sqlite');
        if ($driver) {
            $path = $driver['database'];
            if (!File::exists($path)) {
                $state = File::put($path, '');
                return is_int($state) ? true : false;
            }
        }
        return false;
    }

    /**
     * Drop a SQLite database.
     *
     * @return bool True if the database was successfully dropped, false otherwise.
     */
    public static function dropDatabaseSqlite(): bool
    {
        $driver = static::getDriver('sqlite');
        if ($driver) {
            $path = $driver['database'];
            if (File::exists($path)) {
                return File::delete($path);
            }
        }
        return false;
    }

    /**
     * Check if a database exists.
     *
     * @param string $name The name of the database.
     * @return bool True if the database exists, false otherwise.
     */
    public static function checkDatabase($name): bool
    {
        try {
            return (bool) DB::withQuery(Query::describe($name))->run();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Create the database based on the default driver configuration.
     *
     * @return bool True if the database was successfully created, false otherwise.
     */
    public static function create()
    {
        $driver = static::getDriverDefault();
        if ($driver === 'mysql') {
            return static::makeDatabaseMySql();
        }
        if ($driver === 'sqlite') {
            return static::makeDatabaseSqlite();
        }
        return false;
    }
}

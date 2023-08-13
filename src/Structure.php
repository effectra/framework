<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Core\Exceptions\StructureException;
use Effectra\Fs\Directory;
use Effectra\Fs\Path;

/**
 * Class Structure
 *
 * The Structure class is responsible for scanning and validating the directory structure of an application.
 * It ensures that the required directories and subdirectories are present.
 */
class Structure
{
    /**
     * The main directories that should be present in the application root directory.
     *
     * @var array
     */
    protected static array $main = [
        'app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'tests', 'view'
    ];

    /**
     * The subdirectories that should be present in the 'app' directory.
     *
     * @var array
     */
    protected static array $appDir = [
        'Commands', 'Controllers', 'Middlewares', 'Models', 'Providers'
    ];

    /**
     * The subdirectories that should be present in the 'database' directory.
     *
     * @var array
     */
    protected static array $databaseDir = [
        'migrations', 'schema'
    ];

    /**
     * The subdirectories that should be present in the 'storage' directory.
     *
     * @var array
     */
    protected static array $storageDir = [
        'cache', 'exports', 'imports', 'keys', 'logs'
    ];

    /**
     * The subdirectories that should be present in the 'resources' directory.
     *
     * @var array
     */
    protected static array $resourcesDir = [
        'js', 'css', 'translations'
    ];

    /**
     * The directories that are missing in the application structure.
     *
     * @var array
     */
    protected array $missedDir = [];

    /**
     * Scans the application structure and checks for missing directories.
     *
     * @return bool True if the application structure is valid, False if there are missing directories.
     */
    public function scan(): bool
    {
        // Scan the main directories
        foreach (static::$main as $dir) {
            if (!Directory::isDirectory(Application::appPath($dir))) {
                $this->missedDir[] = Application::appPath($dir);
            }
        }

        // Scan the 'app' directory subdirectories
        foreach (static::$appDir as $dir) {
            if (!Directory::isDirectory(Application::appPath('app' . Path::ds() . $dir))) {
                $this->missedDir[] = Application::appPath('app' . Path::ds() . $dir);
            }
        }

        // Scan the 'database' directory subdirectories
        foreach (static::$databaseDir as $dir) {
            if (!Directory::isDirectory(Application::databasePath($dir))) {
                $this->missedDir[] = Application::databasePath($dir);
            }
        }

        // Scan the 'storage' directory subdirectories
        foreach (static::$storageDir as $dir) {
            if (!Directory::isDirectory(Application::storagePath($dir))) {
                $this->missedDir[] = Application::storagePath($dir);
            }
        }

        // Scan the 'resources' directory subdirectories
        foreach (static::$resourcesDir as $dir) {
            if (!Directory::isDirectory(Application::resourcesPath($dir))) {
                $this->missedDir[] = Application::resourcesPath($dir);
            }
        }

        if (count($this->missedDir) == 0) {
            return true;
        }

        return false;
    }

    /**
     * Throws a StructureException if a required directory is missing.
     *
     * @throws StructureException if a required directory is missing
     */
    public function declareMissedDirectory(): void
    {
        $dirs = array_map(fn($dir) => " $dir\n", $this->missedDir);
        throw new StructureException("Directory is missing:\n\n" . implode(' ', $dirs));
    }

    /**
     * Builds the missing directories in the application structure.
     */
    public function buildFrameworkDirectories(): void
    {
        foreach ($this->missedDir as $dir) {
            Directory::make($dir);
        }
    }

    /**
     * Gets the list of directories that are missing in the application structure.
     *
     * @return array The list of missing directories.
     */
    public function getMissedDirectories(): array
    {
        return $this->missedDir;
    }
}

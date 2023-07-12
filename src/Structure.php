<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Core\Exceptions\StructureException;
use Effectra\Fs\Directory;
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
     * @throws StructureException if a required directory is missing
     */
    public function scan()
    {
        // Scan the main directories
        foreach (Directory::directories(Application::appPath()) as $dir) {
            if (!in_array($dir, static::$main)) {
                $this->missedDir[] = Application::appPath() . $dir;
                throw new StructureException("Directory '$dir' is missing.");
            }
        }

        // Scan the 'app' directory subdirectories
        foreach (Directory::directories(Application::appPath('app')) as $dir) {
            if (!in_array($dir, static::$appDir)) {
                $this->missedDir[] = Application::appPath('app') . $dir;
                throw new StructureException("Directory '$dir' is missing.");
            }
        }

        // Scan the 'database' directory subdirectories
        foreach (Directory::directories(Application::databasePath()) as $dir) {
            if (!in_array($dir, static::$databaseDir)) {
                $this->missedDir[] = Application::databasePath() . $dir;
                throw new StructureException("Directory '$dir' is missing.");
            }
        }

        // Scan the 'storage' directory subdirectories
        foreach (Directory::directories(Application::storagePath()) as $dir) {
            if (!in_array($dir, static::$storageDir)) {
                $this->missedDir[] = Application::storagePath() . $dir;
                throw new StructureException("Directory '$dir' is missing.");
            }
        }

        // Scan the 'resources' directory subdirectories
        foreach (Directory::directories(Application::resourcesPath()) as $dir) {
            if (!in_array($dir, static::$resourcesDir)) {
                $this->missedDir[] = Application::resourcesPath() . $dir;
                throw new StructureException("Directory '$dir' is missing.");
            }
        }
    }

    /**
     * Builds the missing directories in the application structure.
     */
    public function buildFramework()
    {
        foreach ($this->missedDir as $dir) {
            Directory::make($dir);
        }
    }
}

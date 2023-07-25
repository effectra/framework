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
     * @return bool True if the application structure is valid, False if there are missing directories.
     */
    public function scan():bool
    {
        // Scan the main directories
        foreach (Directory::directories(Application::appPath()) as $dir) {
            if (!Directory::isDirectory($dir)) {
                $this->missedDir[] = Application::appPath() . $dir;
            }
        }

        // Scan the 'app' directory subdirectories
        foreach (Directory::directories(Application::appPath('app')) as $dir) {
            if (!Directory::isDirectory($dir)) {
                $this->missedDir[] = Application::appPath('app') . $dir;
            }
        }

        // Scan the 'database' directory subdirectories
        foreach (Directory::directories(Application::databasePath()) as $dir) {
            if (!Directory::isDirectory($dir)) {
                $this->missedDir[] = Application::databasePath() . $dir;
            }
        }

        // Scan the 'storage' directory subdirectories
        foreach (Directory::directories(Application::storagePath()) as $dir) {
            if (!Directory::isDirectory($dir)) {
                $this->missedDir[] = Application::storagePath() . $dir;
            }
        }

        // Scan the 'resources' directory subdirectories
        foreach (Directory::directories(Application::resourcesPath()) as $dir) {
            if (!Directory::isDirectory($dir)) {
                $this->missedDir[] = Application::resourcesPath() . $dir;
            }
        }

        if(count($this->missedDir) == 0){
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
        $dirs = join(', ', $this->missedDir);
        throw new StructureException("Directory '$dirs' is missing.");
    }

    /**
     * Builds the missing directories in the application structure.
     */
    public function buildFrameworkDirectories():void
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
    public function getMissedDirectories():array
    {
        return $this->missedDir;
    }
}

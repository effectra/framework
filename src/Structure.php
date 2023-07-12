<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Core\Exceptions\StructureException;
use Effectra\Fs\Directory;

class Structure
{
    protected static array $main = [
        'app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'tests', 'view'
    ];

    protected static array $appDir = [
        'Commands', 'Controllers', 'Middlewares', 'Models', 'Providers'
    ];

    protected static array $databaseDir = [
        'migrations', 'schema'
    ];

    protected static array $storageDir = [
        'cache', 'exports', 'imports', 'keys', 'logs'
    ];

    protected static array $resourcesDir = [
        'js', 'css', 'translations'
    ];

    protected array $missedDir = [];

    public function scan()
    {
        foreach (Directory::directories(Application::appPath()) as $dir) {
            if (!in_array($dir, static::$main)) {
                $this->missedDir[] = Application::appPath() . $dir;
                throw new StructureException("directory '$dir' is missing");
            }
        }

        foreach (Directory::directories(Application::appPath('app')) as $dir) {
            if (!in_array($dir, static::$appDir)) {
                $this->missedDir[] = Application::appPath('app') . $dir;
                throw new StructureException("directory '$dir' is missing");
            }
        }

        foreach (Directory::directories(Application::databasePath()) as $dir) {
            if (!in_array($dir, static::$databaseDir)) {
                $this->missedDir[] = Application::databasePath() . $dir;
                throw new StructureException("directory '$dir' is missing");
            }
        }

        foreach (Directory::directories(Application::storagePath()) as $dir) {
            if (!in_array($dir, static::$storageDir)) {
                $this->missedDir[] = Application::storagePath() . $dir;
                throw new StructureException("directory '$dir' is missing");
            }
        }

        foreach (Directory::directories(Application::resourcesPath()) as $dir) {
            if (!in_array($dir, static::$resourcesDir)) {
                $this->missedDir[] = Application::resourcesPath() . $dir;
                throw new StructureException("directory '$dir' is missing");
            }
        }
    }

    public function buildFramework()
    {
        foreach ($this->missedDir as $dir) {
            Directory::make($dir);
        }
    }
}

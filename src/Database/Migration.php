<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Core\Container\DiClasses;
use Effectra\Core\Facades\DB;
use Effectra\Fs\Directory;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Effectra\SqlQuery\Query;
use Effectra\SqlQuery\Table;
use Symfony\Component\VarDumper\VarDumper;

class Migration
{
    /**
     * @var array $appliedMigrations the applied migration.
     */
    protected array $appliedMigrations = [];

    /**
     * @var bool $migrated 
     */
    protected bool $migrated = false;

    /**
     * @return string the migrations folder path
     */
    public function dir(): string
    {
        return Application::databasePath('migrations');
    }

    public function __construct()
    {
        $this->createMigrationsTable();
    }

    /**
     * Apply migrations.
     *
     * @param string $act The action to perform (up or down).
     * @return void
     */
    public function applyMigrations($act = 'up'): void
    {
        try {
            $appliedMigrations = $this->getAppliedMigrations();
            
            $dir = $this->dir();
            $files = $act == 'up' ? Directory::files($dir) : array_reverse(Directory::files($dir));

            if ($act == 'down') {
                $files = array_reverse($files);
                $appliedMigrationsAsDown = array_filter($appliedMigrations, fn ($m) =>  $m['type'] == 'down' && $m['migration']);
                $migrationFilesDown = array_map(fn ($m) => $m['migration'], $appliedMigrationsAsDown);

                foreach ($appliedMigrations as $appliedMigration) {
                    if ($appliedMigration['type'] === 'up' && !in_array($appliedMigration['migration'], $migrationFilesDown)) {
                        $this->migrateWithLog($appliedMigration['migration'], $act);
                    }
                }
            } elseif ($act == 'up') {
                $migrationFiles = array_map(fn ($m) => $m['migration'], $appliedMigrations);
                $toApplyMigrations = empty($migrationFiles) ? $files : array_diff($files, $migrationFiles);

                foreach ($toApplyMigrations as $migration) {
                    $this->migrateWithLog($migration, $act);
                }
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Record the applied migration.
     *
     * @param string $migration The migration filename.
     * @return void
     */
    private function record(string $migration): void
    {
        $this->appliedMigrations[] = $migration;
    }

    /**
     * Get the list of applied migrations.
     *
     * @return array The applied migrations.
     */
    public function appliedMigrations(): array
    {
        return $this->appliedMigrations;
    }

    /**
     * Save the migration to the migrations table.
     *
     * @param string $migration The migration filename.
     * @param string $act The action to perform (up or down).
     * @return void
     */
    private function save(string $migration, string $act): void
    {
        MigrationModel::create([['migration' => $migration, 'type' => $act]]);
    }

    /**
     * Create the migrations table if it doesn't exist.
     *
     * @return void
     */
    private function createMigrationsTable(): void
    {

        $query = Query::createTable('migrations', function (Table $table) {
            $table->autoIncrement();
            $table->string('migration');
            $table->string('type')->default('up');
            $table->timestamps();
        })->ifNotExists();
        DB::query((string) $query)->run();
    }

    /**
     * Get the list of applied migrations from the migrations table.
     *
     * @return array The applied migrations.
     */
    private function getAppliedMigrations(): array
    {
        $migrations = [];
        foreach (MigrationModel::all(['migration', 'type']) as $item) {
            $migrations[] = $item->toArray();
        }
        return $migrations;
    }

    /**
     * Drop the migrations table.
     *
     * @return bool True if the table was dropped successfully, false otherwise.
     */
    public function dropMigration(): bool
    {
        $query = Query::drop()->table('migrations')->dropTable();
        $result = DB::query((string) $query)->run();

        return $result;
    }

    /**
     * Empty the migrations table.
     *
     * @return bool True if the table was truncated successfully, false otherwise.
     */
    public function emptyMigration(): bool
    {
        return MigrationModel::truncate();
    }

    /**
     * Apply the migration defined in the given migration file.
     *
     * @param string $migrationFile The migration filename.
     * @param string $act The action to perform (up or down).
     * @throws \Exception When migration class or methods are undefined, or an error occurs during migration.
     * @return void
     */
    public function migrate(string $migrationFile, string $act): void
    {

        // Get the directory path for migrations
        $dir = $this->dir();

        // Load the migration file
        require_once $dir . Path::ds() . $migrationFile;

        // Extract the filename and class name from the migration file
        $filename = basename($migrationFile);
        $className = '\App\Migrations\\' . pathinfo($filename, PATHINFO_FILENAME);

        // Check if the class exists and if the migration directory exists
        if (!class_exists($className) || !File::exists($dir)) {
            throw new \Exception("$className is undefined or the migration directory does not exist.");
        }

        // Load the migration class instance
        $instance = DiClasses::load($className);

        // Perform migration based on the action (up or down)
        if ($act === 'up') {
            if (!method_exists($instance, 'up')) {
                throw new \Exception("Undefined method 'up' in $filename.");
            }
            $instance->up();
            $this->migrated = true;
        } elseif ($act === 'down') {
            if (!method_exists($instance, 'down')) {
                throw new \Exception("Undefined method 'down' in $filename.");
            }
            $instance->down();
            $this->migrated = true;
        }
        // Save the migration and record if migration was successful
        if ($this->migrated) {
            $this->save($filename, $act);
            $this->record($filename);
        } else {
            throw new \Exception("Error processing migration: $filename");
        }
    }


    /**
     * Check if a migration file has been migrated with the specified action.
     *
     * @param string $file The migration filename to check.
     * @param string $act The action to check against (up or down).
     * @return bool True if the migration has been migrated with the specified action, false otherwise.
     */
    public function isMigrated(string $file,string $act): bool
    {
        foreach ($this->getAppliedMigrations() as $m) {
            if ($m['type'] == $act && $file == $m['migration']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Apply the migration defined in the given migration file with logging.
     *
     * @param string $migrationFile The migration filename.
     * @param string $act The action to perform (up or down).
     * @return void
     */
    public function migrateWithLog(string $migrationFile, string $act): void
    {
        $filePath = $this->dir() . Path::ds() . $migrationFile;
        try {
            $this->migrate($migrationFile, $act);
            Application::log()->info("Migration '{$filePath}' successfully $act.");
        } catch (\Throwable $e) {
            Application::log()->error("Error during migration '{$filePath}': " . $e->getMessage());
            VarDumper::dump($e);
        }
    }
}

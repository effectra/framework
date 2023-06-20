<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Core\Facades\DB;
use Effectra\Fs\Directory;
use Effectra\Fs\Path;
use Effectra\SqlQuery\Query;
use Effectra\SqlQuery\Table;

class Migration
{
    protected array $appliedMigrations = [];

    /**
     * Apply migrations.
     *
     * @param string $act The action to perform (up or down).
     * @return void
     */
    public function applyMigrations($act = 'up')
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];
        $dir = Application::databasePath('migrations');
        $files = Directory::files($dir);
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            require_once $dir . Path::ds() . $migration;

            $filename = basename($migration);
            $className = '\App\Migrations\\' . pathinfo($filename, PATHINFO_FILENAME);

            $instance = new $className();
            if ($act === 'up') {
                $instance->up();
            } else if ($act === 'down') {
                $instance->down();
            }

            $this->save($filename);
            $this->record($filename);
        }
    }

    /**
     * Record the applied migration.
     *
     * @param string $migration The migration filename.
     * @return void
     */
    public function record(string $migration)
    {
        $this->appliedMigrations[] = $migration;
    }

    /**
     * Get the list of applied migrations.
     *
     * @return array The applied migrations.
     */
    public function appliedMigrations()
    {
        return $this->appliedMigrations;
    }

    /**
     * Save the migration to the migrations table.
     *
     * @param string $migration The migration filename.
     * @return void
     */
    public function save($migration)
    {
        MigrationModel::data(['migration' => $migration])->create();
    }

    /**
     * Create the migrations table if it doesn't exist.
     *
     * @return void
     */
    public function createMigrationsTable()
    {
        $table = new Table('migrations');
        $table->autoIncrement();
        $table->string('migration');
        $table->timestamps();
        $query = $table->buildQuery('create');
        DB::withQuery($query)->run();
    }

    /**
     * Get the list of applied migrations from the migrations table.
     *
     * @return array The applied migrations.
     */
    public function getAppliedMigrations()
    {
        $query = (string) Query::select('migrations')->selectColumns('migration');

        $migrations =  DB::withQuery($query)->get();

        $applied = [];
        if ($migrations) {
            foreach ($migrations as $m) {
                $applied[] = $m['migration'];
            }
        }
        return $applied;
    }

    /**
     * Drop the migrations table.
     *
     * @return bool True if the table was dropped successfully, false otherwise.
     */
    public function dropMigration()
    {
        $query = (string) Query::drop('migrations')->dropTable();
        $result = DB::withQuery($query)->run();

        return $result;
    }

    /**
     * Empty the migrations table.
     *
     * @return bool True if the table was truncated successfully, false otherwise.
     */
    public function emptyMigration()
    {
        $query = (string) Query::delete('migrations')->truncate();
        $result = DB::withQuery($query)->run();

        return $result;
    }
}

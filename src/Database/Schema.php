<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Facades\DB;
use Effectra\SqlQuery\Table;

/**
 * The Schema class provides methods for creating and modifying database tables.
 */
class Schema
{
    /**
     * Create a new database table.
     *
     * @param string $tableName The name of the table to create.
     * @param callable $callback The callback function to define the table structure.
     * @return void
     */
    public static function create(string $tableName, callable $callback)
    {
        $table = new Table($tableName);
        call_user_func($callback, $table);
        $query = $table->buildQuery('create');
        DB::withQuery($query)->run();
    }

    /**
     * Modify an existing database table.
     *
     * @param string $tableName The name of the table to modify.
     * @param callable $callback The callback function to define the table modifications.
     * @return void
     */
    public static function table(string $tableName, callable $callback)
    {
        $table = new Table($tableName);
        $cols = DB::table($tableName)->getColumnsField();
        $table->setColumns($cols);
        call_user_func($callback, $table);
        $query = $table->buildQuery('modify');
        if (!empty($query)) {
            DB::withQuery($query)->run();
        }
    }
}

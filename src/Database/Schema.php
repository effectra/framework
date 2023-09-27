<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Facades\DB;
use Effectra\SqlQuery\Query;
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
     * @param bool $exists Specify whether the table should only be created if exist or not.
     * @return static
     */
    public static function create(string $tableName, callable $callback, bool $exists = false)
    {
        $query = Query::createTable($tableName, $callback);
        if ($exists === true) {
            $query->ifExists();
        }
        if ($exists === false) {
            $query->ifNotExists();
        }
        DB::query((string) $query)->run();
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
        $query = Query::updateTable($tableName, $callback);
        DB::query($query)->run();
    }
}

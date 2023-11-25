<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Database\DB;
use Effectra\SqlQuery\Query;

/**
 * The Schema class provides methods for creating and modifying database tables.
 */
class Schema
{
    private static $query;

    /**
     * get SQL query are used 
     * @return Query
     */
    public static function getQuery():Query
    {
        return static::$query;
    }

    /**
     *  set SQL query 
     * @param Query $query 
     * @return void
     */
    public static function setQuery($query):void
    {
        static::$query = $query;
    }

    /**
     * get database connection
     */
    public static function db(): DB
    {
        return app()->container()->get(DB::class);
    }

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
        static::setQuery($query);

        static::db()->query((string) $query)->run();

        return new static();
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
        if (!static::tableExists($tableName)) {
            throw new \Exception("Table '$tableName' not exists");
        }
        $query = Query::updateTable($tableName, $callback);

        $listColumnsUpdated = array_unique(array_map(fn ($item) => $item['column_name'], $query->getAttribute('cols') ?? []));

        foreach ($listColumnsUpdated as $col) {
            if (in_array($col, static::listOfColumns($tableName))) {
                return;
            }
        }

        static::db()->query((string) $query)->run();
    }

    /**
     * get list columns of a table.
     * @param string $tableName The name of the table to modify.
     * @return array
     */
    public static function listOfColumns(string $tableName): array
    {
        $listColumnsQuery = Query::info()->listColumns($tableName);

        $data = static::db()->query((string) $listColumnsQuery)->fetch();

        return $data ? array_map(fn ($item) => $item['Field'], $data) : [];
    }

    /**
     * check if table exists in db
     * @param string $tableName The name of the table to modify.
     * @return bool
     */
    public static function tableExists(string $tableName): bool
    {
        $query = Query::info()->tableExists($tableName);
        $data = static::db()->query((string) $query)->fetch();

        return empty($data) ? false : true;
    }

    /**
     * dd query are used
     */
    public function ddQuery()
    {
        dd(static::getQuery());
    }
}

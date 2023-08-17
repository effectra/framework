<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Database\DB;
use Effectra\SqlQuery\Query;
use InvalidArgumentException;

/**
 * The ModelBase trait provides common database operations for models.
 */
trait ModelBase 
{
    /**
     * Get the database instance.
     *
     * @return DB The database instance.
     */
    private static function db(): DB
    {
        return Application::container()->get(DB::class);
    }

    /**
     * Format the data in a pretty format.
     *
     * @param array $data The data to format.
     * @return array The formatted data.
     */
    public static function pretty($data)
    {
        $callback = function ($item) {
            if (isset(static::$display)) {
                foreach (static::$display['json'] as $key) {
                    $item[$key] = json_decode($item[$key], true);
                }
            }

            return $item;
        };

        return array_map($callback, $data);
    }

    /**
     * The data to be used for the model.
     *
     * @var array
     */
    protected static $data = [];

    /**
     * Set the data for the model.
     *
     * @param array $data The data to set.
     * @return self The model instance.
     */
    public static function data($data): self
    {
        static::$data = $data;

        return new static;
    }

    /**
     * Get the values from the data.
     *
     * @return array The values.
     */
    private static function values()
    {
        return array_values(static::$data);
    }

    /**
     * Get the columns from the data.
     *
     * @return array The columns.
     */
    private static function columns()
    {
        return array_keys(static::$data);
    }

    /**
     * Retrieve all records from the model's table.
     *
     * @return array|null The retrieved records, or null if no records found.
     */
    public static function all()
    {
        $query = (string) Query::select(static::$table);
        $data = static::db()->withQuery($query)->get();

        return static::pretty($data);
    }

    /**
     * Find a record by ID.
     *
     * @param mixed $id The ID of the record.
     * @param bool|null $associative Whether to return an associative array or object.
     * @return object|null|array The retrieved record, or null if no record found.
     */
    public static function find($id, ?bool $associative = null)
    {
        $query = (string) Query::select(static::$table)->whereId($id)->limit(1);
        $result = static::db()->withQuery($query)->get();
        if ($result) {
            $data = static::pretty($result)[0];

            return $associative ? $data : (object) $data;
        }
        return null;
    }

    /**
     * Find records based on conditions.
     *
     * @param array $conditions The conditions to match.
     * @param bool|null $associative Whether to return an associative array or object.
     * @return object|null|array The retrieved record, or null if no record found.
     */
    public static function where($conditions, ?bool $associative = null)
    {
        $query = (string) Query::select(static::$table)->where($conditions);
        $result = static::db()->withQuery($query)->get();
        if ($result) {
            $data = static::pretty($result);

            return $associative ? $data : (object) $data;
        }
        return null;
    }

    /**
     * Create a new record.
     *
     * @return object|false The created record, or false if creation failed.
     * @throws \Exception If no values specified for insertion.
     */
    public static function create()
    {
        $query = Query::insert(static::$table);

        $query->columns(static::columns());
        $query->values(static::values());

        if (empty($query->getValues())) {
            throw new \Exception("No values specified for insertion.");
        }
        $success = static::db()->withQuery((string) $query)->run();
        if ($success) {
            return (object) ['id' => static::db()->getConn()->lastInsertId()];
        }
        return false;
    }

    /**
     * Update a record.
     *
     * @param mixed $id The ID of the record to update.
     * @return bool Whether the update operation was successful.
     * @throws \Exception If no values specified for update.
     */
    public static function update($id)
    {
        $query = Query::Update(static::$table)->where(['id' => $id]);

        $query->values(static::values());
        $query->columns(static::columns());

        if (empty($query->getValues())) {
            throw new \Exception("No values specified for insertion.");
        }

        $result = static::db()->withQuery($query)->run($query->combineColumnsValues());
        return $result;
    }

    /**
     * Update a record.
     *
     * @param mixed $conditions The conditions of the record to update.
     * @return bool Whether the update operation was successful.
     * @throws \Exception If no values specified for update.
     */
    public static function updateWhere($conditions)
    {
        $query = Query::Update(static::$table)->where($conditions);

        $query->values(static::values());
        $query->columns(static::columns());

        if (empty($query->getValues())) {
            throw new \Exception("No values specified for insertion.");
        }
        $result = static::db()->withQuery($query)->run($query->combineColumnsValues());
        return $result;
    }


    /**
     * Search for records based on conditions.
     *
     * @param array $conditions The conditions to match.
     * @return array|null The retrieved records, or null if no records found.
     * @throws InvalidArgumentException If invalid conditions provided.
     */
    public static function search($conditions)
    {
        if (!$conditions || empty($conditions)) {
            throw new InvalidArgumentException('Invalid conditions.');
        }
        $query = (string) Query::select(static::$table)->where($conditions, 'LIKE');
        $result = static::db()->withQuery($query)->get();
        if ($result) {
            return $result;
        }
        return null;
    }

    /**
     * Delete a record by ID.
     *
     * @param mixed $id The ID of the record to delete.
     * @return bool Whether the delete operation was successful.
     */
    public static function delete($id)
    {
        $query = Query::delete(static::$table)->deleteById($id);
        $result = static::db()->withQuery($query)->run();

        return $result;
    }

    /**
     * Delete all records from the model's table.
     *
     * @return bool Whether the delete operation was successful.
     */
    public static function deleteAll()
    {
        return static::truncate();
    }

    /**
     * Truncate the model's table.
     *
     * @return bool Whether the truncate operation was successful.
     */
    public static function truncate()
    {
        $query = Query::delete(static::$table)->truncate();
        $result = static::db()->withQuery($query)->run();

        return $result;
    }
}

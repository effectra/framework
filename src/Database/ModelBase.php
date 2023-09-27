<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Database\Data\DataRules;
use Effectra\Database\DB;
use Effectra\SqlQuery\Condition;
use Effectra\SqlQuery\Query;

/**
 * The ModelBase trait provides common database operations for models.
 */
trait ModelBase
{
    protected static array $data = [];

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
     * Display data fetched from the database based on the model's data rules.
     *
     * @param \PDOStatement $stmt The PDO statement containing the fetched data.
     *
     * @return array|null An array of fetched data or null if no data rules are defined.
     */
    public static function displayData($stmt): array
    {
        // Check if the method getDataRules() exists in the current class.
        if (!method_exists(static::class, 'getDataRules')) {
            return null;
        }

        // Get the data rules for the model.
        $dataRules = static::getDataRules();

        if ($dataRules) {
            // Fetch data using the DataRules attributes and rules.
            return $stmt->fetchPretty(function (DataRules $r) use ($dataRules) {
                $r->setAttributes($dataRules->getAttributes());
                $r->setRules($dataRules->getRules());
            });
        }

        // If no data rules are defined, fetch data without formatting.
        return $stmt->fetch();
    }

    /**
     * Format the data in a pretty format.
     * @param mixed $data The data to be inserted.
     *
     * @throws DataValidatorException If the data is not valid.
     */
    public static function optimizeData($data, callable $rules)
    {
        static::db()->prettyData($data, $rules);
    }

    /**
     * Set the data for the model.
     *
     * @param array $data The data to set.
     */
    public static function data($data)
    {
        static::$data = $data;
        return new static;
    }

    /**
     * Retrieve all records from the model's table.
     *
     * @return array|null The retrieved records, or null if no records found.
     */
    public static function all()
    {
        $query = (string) Query::select(static::$table)->all();
        $stmt = static::db()->query($query);

        return static::displayData($stmt);
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
        $data = static::db()->query($query);
        if ($data) {
            return $associative ? static::displayData($data) : (object) static::displayData($data);
        }
        return null;
    }

    /**
     * Find records based on conditions.
     *
     * @param Condition $conditions The conditions to match.
     * @param bool|null $associative Whether to return an associative array or object.
     * @return object|null|array The retrieved record, or null if no record found.
     */
    public static function where(Condition $conditions, ?bool $associative = null)
    {
        $query = (string) Query::select(static::$table)->whereConditions($conditions);
        $data = static::db()->query($query)->fetch();
        if ($data) {
            return $associative ? static::displayData($data) : (object) static::displayData($data);
        }
        return null;
    }

    /**
     * Create a new record. 
     *
     * @return bool — True if the data was successfully inserted, false otherwise.
     */
    public static function create()
    {
        return static::db()->table(static::$table)->insert(static::$data);
    }

    /**
     * Get the ID of the last inserted row.
     * 
     * @return string|false — Returns the last inserted ID or false on failure.
     */
    public static function getId(): string|false
    {
        return static::db()->lastInsertId();
    }

    /**
     * Update a record.
     *
     * @param mixed $id The ID of the record to update.
     * @return bool Whether the update operation was successful.
     */
    public static function update($id)
    {
        return static::db()->table(static::$table)->update(static::$data, (new Condition())->where(['id' => $id]));
    }

    /**
     * Update a record.
     *
     * @param Condition $conditions The conditions of the record to update.
     * @return bool Whether the update operation was successful.
     */
    public static function updateWhere(Condition $conditions)
    {
        return static::db()->table(static::$table)->update(static::$data, $conditions);
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
        if (empty($conditions)) {
            throw new \InvalidArgumentException('Invalid conditions.');
        }
        $query = (string) Query::select(static::$table)->whereLike($conditions);
        $data = static::db()->query($query);

        return static::displayData($data);
    }

    /**
     * Delete a record by ID.
     *
     * @param mixed $id The ID of the record to delete.
     * @return bool Whether the delete operation was successful.
     */
    public static function delete($id)
    {
        return static::db()->table(static::$table)->delete((new Condition())->where(['id' => ':id']), ['id' => $id]);
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
        $query = Query::truncate(static::$table);
        return static::db()->query($query)->run();
    }

    /**
     * limit rows of table records.
     * @param int $from
     * @param null|int $to
     * @return array|null The retrieved records, or null if no records found.
     */
    public static function limit(int $from, ?int $to = null): ?array
    {
        $query = Query::select(static::$table)->limit($from, $to);
        $data = static::db()->query($query);

        return static::displayData($data);
    }
}

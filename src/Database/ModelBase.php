<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Database\Data\DataOptimizer;
use Effectra\Database\Data\DataRules;
use Effectra\Database\DB;
use Effectra\SqlQuery\Condition;
use Effectra\SqlQuery\Query;

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
     * Display data fetched from the database based on the model's data rules.
     *
     * @param DB $stmt The PDO statement containing the fetched data.
     *
     * @return ?array An array of fetched data or null if no data rules are defined.
     */
    public static function displayData($stmt): ?array
    {
        // Check if the method getDataRules() exists in the current class.
        if (!method_exists(static::class, 'getDataRules')) {
            return null;
        }

        // Get the data rules for the model.
        $dataRules = static::getDataRules();

        if ($dataRules) {

            // Fetch data using the DataRules attributes and rules.
            $data =  $stmt->fetchPretty(function (DataRules $r) use ($dataRules) {
                $r->setAttributes($dataRules->getAttributes());
                $r->setRules($dataRules->getRules());
            });
        } else {
            // If no data rules are defined, fetch data without formatting.
            $data = $stmt->fetch();
        }

        return $data ? static::data($data)->data : null;
    }

    /**
     * Set the data for the model.
     *
     * @param array $data The data to set.
     */
    public static function data($data)
    {
        return new static($data);
    }

    /**
     * Format the data in a pretty format.
     * @param mixed $data The data to be inserted.
     * @param callable $rules — A callback function to define rules using DataRules.
     */
    public static function dataOptimized($data, callable $rules)
    {
        $op = new DataOptimizer($data);
        return static::data($op->optimize($rules));
    }

    /**
     * Retrieve all records from the model's table.
     *
     * @return ?array The retrieved records, or null if no records found.
     */
    public static function all(): ?array
    {
        $query = (string) Query::select(static::getTableName())->all();
        $stmt = static::db()->query($query);

        return static::displayData($stmt);
    }

    /**
     * Find a record by ID.
     *
     * @param mixed $id The ID of the record.
     * @param bool|null $associative Whether to return an associative array or object.
     * @return ?array The retrieved record, or null if no record found.
     */
    public static function find($id, ?bool $associative = null): ?array
    {
        $query = (string) Query::select(static::getTableName())->all()->where(['id' => $id])->limit(1);
        $data = static::db()->query($query);
        if ($data) {
            $data =  static::displayData($data);
            return $associative ? $data : (object) $data;
        }
        return null;
    }

    /**
     * Find records based on conditions.
     *
     * @param Condition $conditions The conditions to match.
     * @param bool|null $associative Whether to return an associative array or object.
     * @return ?array The retrieved record, or null if no record found.
     */
    public static function where(Condition $conditions, ?bool $associative = null): ?array
    {
        $query = (string) Query::select(static::getTableName())->whereConditions($conditions);
        $data = static::db()->query($query)->fetch();
        if ($data) {
            return $associative ? static::displayData($data) : (object) static::displayData($data);
        }
        return null;
    }

    /**
     * limit rows of table records.
     * @param int $from
     * @param null|int $to
     * @return ?array The retrieved records, or null if no records found.
     */
    public static function limit(int $from, ?int $to = null): ?array
    {
        $query = Query::select(static::getTableName())->limit($from, $to);
        $data = static::db()->query($query);

        return static::displayData($data);
    }

    /**
     * Search for records based on conditions.
     *
     * @param array $conditions The conditions to match.
     * @return  ?array  The retrieved records, or null if no records found.
     * @throws InvalidArgumentException If invalid conditions provided.
     */
    public static function search($conditions): ?array
    {
        if (empty($conditions)) {
            throw new \InvalidArgumentException('Invalid conditions.');
        }
        $query = (string) Query::select(static::getTableName())->columns(array_keys($conditions))->whereLike($conditions);
        $data = static::db()->query($query);

        return static::displayData($data);
    }

    /**
     * Create a new record. 
     *
     * @return bool — True if the data was successfully inserted, false otherwise.
     */
    public function create()
    {
        return static::db()->table(static::getTableName())->insert($this->data);
    }

    /**
     * Update a record.
     *
     * @param mixed $id The ID of the record to update.
     * @return bool Whether the update operation was successful.
     */
    public function update($id)
    {
        return static::db()->table(static::getTableName())->update($this->data, (new Condition())->where(['id' => $id]));
    }

    /**
     * Update a record.
     *
     * @param Condition $conditions The conditions of the record to update.
     * @return bool Whether the update operation was successful.
     */
    public function updateWhere(Condition $conditions)
    {
        return static::db()->table(static::getTableName())->update($this->data, $conditions);
    }

    /**
     * Delete a record by ID.
     *
     * @param mixed $id The ID of the record to delete.
     * @return bool Whether the delete operation was successful.
     */
    public static function delete($id)
    {
        return static::db()->table(static::getTableName())->delete((new Condition())->where(['id' => ':id']), ['id' => $id]);
    }

    /**
     * Delete a record by ID.
     *
     * @param Condition $conditions The conditions of the record to update.
     * @return bool Whether the delete operation was successful.
     */
    public static function deleteWhere(Condition $conditions)
    {
        return static::db()->table(static::getTableName())->delete((new Condition())->whereConditions($conditions));
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
        $query = Query::truncate(static::getTableName());
        return static::db()->query($query)->run();
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

}

<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\SqlQuery\Condition;
use Effectra\SqlQuery\Query;

trait GetFromDatabaseTrait
{
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
}

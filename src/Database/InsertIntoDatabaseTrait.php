<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\SqlQuery\Condition;
use Effectra\SqlQuery\Query;

trait InsertIntoDatabaseTrait
{
    /**
     * Create a new record. 
     *
     * @return bool True if the data was successfully inserted, false otherwise.
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

<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

/**
 * The ModelInterface defines the contract for a model class.
 */
interface ModelInterface
{
    /**
     * Create a new instance of the model with the given data.
     *
     * @param mixed $data The data to populate the model.
     * @return self The new instance of the model.
     */
    public static function data($data): self;

    /**
     * Get all records of the model.
     *
     * @return mixed The records of the model.
     */
    public static function all();

    /**
     * Find a record by its ID.
     *
     * @param mixed $id The ID of the record.
     * @param bool|null $associative Whether to return the record as an associative array.
     * @return mixed The found record.
     */
    public static function find($id, ?bool $associative = null);

    /**
     * Find records based on the given conditions.
     *
     * @param mixed $conditions The conditions to search for.
     * @param bool|null $associative Whether to return the records as associative arrays.
     * @return mixed The found records.
     */
    public static function where($conditions, ?bool $associative = null);

    /**
     * Create a new record.
     *
     * @return mixed The created record.
     */
    public static function create();

    /**
     * Update a record by its ID.
     *
     * @param mixed $id The ID of the record to update.
     * @return mixed The updated record.
     */
    public static function update($id);

    /**
     * Update records based on the given conditions.
     *
     * @param mixed $conditions The conditions to search for.
     * @return mixed The updated records.
     */
    public static function updateWhere($conditions);

    /**
     * Search for records based on the given conditions.
     *
     * @param mixed $conditions The conditions to search for.
     * @return mixed The found records.
     */
    public static function search($conditions);

    /**
     * Delete a record by its ID.
     *
     * @param mixed $id The ID of the record to delete.
     * @return mixed The deleted record.
     */
    public static function delete($id);

    /**
     * Delete all records of the model.
     *
     * @return mixed The result of the deletion operation.
     */
    public static function deleteAll();

    /**
     * Truncate the model's table.
     *
     * @return mixed The result of the truncation operation.
     */
    public static function truncate();
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Database\Data\DataRules;

/**
 * Class Model
 *
 * This class represents a base model for database operations.
 */
class Model
{
    protected static string $table = "";
    /**
     * @param array $data represent model data
     */
    public function __construct(public array $data = [])
    {
    }
    /**
     * Get a new instance of DataRules.
     *
     * @return DataRules
     */
    final public static function rule(): DataRules
    {
        return new DataRules();
    }

    /**
     * Get the data rules for the model.
     *
     * @return DataRules|null
     */
    final public static function getDataRules(): ?DataRules
    {
        if (static::display() instanceof DataRules) {
            return static::display();
        }
        return null;
    }

    /**
     * Display the data rules for the model.
     *
     */
    public static function display()
    {
        return static::rule()->integer('id');
    }

    /**
     * get table name of model
     */
    public static function getTableName(): string
    {
        return static::$table;
    }

    /**
     * Create a ModelCollection from the current instance's data.
     *
     * @return ModelCollection A new ModelCollection containing the current instance's data.
     */
    public function collection(): ModelCollection
    {
        return new ModelCollection($this->data);
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Database\Data\DataRules;

/**
 * Class Model
 *
 * This class represents a base model for database operations.
 *
 */
class Model
{
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
}

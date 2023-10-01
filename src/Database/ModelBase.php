<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Core\Application;
use Effectra\Database\Data\DataOptimizer;
use Effectra\Database\Data\DataRules;
use Effectra\Database\DB;

/**
 * The ModelBase trait provides common database operations for models.
 */
trait ModelBase
{
    use GetFromDatabaseTrait, InsertIntoDatabaseTrait;
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

}

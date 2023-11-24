<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

use Effectra\Database\Contracts\DBInterface;
use Effectra\Database\DB;
use Effectra\Database\Model as DatabaseModel;

/**
 * Class Model
 *
 * This class represents a base model for database operations.
 */
class Model extends DatabaseModel
{
     /*
    * Get a new database connection instance.
    *
    * @return DBInterface
    */
     public static function getDatabaseConnection(): DBInterface
     {
          $conn = AppDatabase::connect();
          DB::createConnection($conn);
          return new DB();
     }

}

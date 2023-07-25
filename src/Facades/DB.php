<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Database\DB as DatabaseDB;
use Effectra\Core\Facade;

/**
 * @method static \Effectra\Database\DB getQuery(): string
 * @method static \Effectra\Database\DB getStatement(): PDOStatement|false
 * @method static \Effectra\Database\DB setConn(PDO $conn)
 * @method static \Effectra\Database\DB withQuery($query): self
 * @method static \Effectra\Database\DB withStatement(PDOStatement|false $stmt): self
 * @method static \Effectra\Database\DB query($query = ''): string
 * @method static \Effectra\Database\DB run(array|null $params = null): bool
 * @method static \Effectra\Database\DB get(): array
 * @method static \Effectra\Database\DB data(array $data): self
 * @method static \Effectra\Database\DB table(string $name):self
 * @method static \Effectra\Database\DB insert($data):void
 */

class DB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseDB::class;
    }
}

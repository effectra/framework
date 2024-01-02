<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Database\DB as DatabaseDB;
use Effectra\Core\Facade;

/**
 * @method static \Effectra\Database\DB  public static function createConnection(ConnectionInterface $connection)
 * @method static \Effectra\Database\DB  public static function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
 * @method static \Effectra\Database\DB  public static function getEventDispatcher(): EventDispatcherInterface
 * @method static \Effectra\Database\DB  public static function getConnection(): PDO
 * @method static \Effectra\Database\DB  public function query(string $query): self
 * @method static \Effectra\Database\DB  public function statement(): PDOStatement|false
 * @method static \Effectra\Database\DB  public function table(string $table): self
 * @method static \Effectra\Database\DB  public function beginTransaction(): bool
 * @method static \Effectra\Database\DB  public function commit(): bool
 * @method static \Effectra\Database\DB  public function quote(string $string, int $type = PDO::PARAM_STR): string|false
 * @method static \Effectra\Database\DB  public function rollback(): bool
 * @method static \Effectra\Database\DB  public function lastInsertId(): string|false
 * @method static \Effectra\Database\DB  public function inTransaction(): bool
 * @method static \Effectra\Database\DB  public function errorCode(): ?string
 * @method static \Effectra\Database\DB  public function errorInfo()
 * @method static \Effectra\Database\DB  public function exec(string $statement): int|false
 * @method static \Effectra\Database\DB  public function getAttribute(int $attribute): mixed
 * @method static \Effectra\Database\DB  public function getAvailableDrivers(): array
 * @method static \Effectra\Database\DB  public  function setAttribute(int $attribute, mixed $value): bool
 * @method static \Effectra\Database\DB  public function run(?array $params = null): bool
 * @method static \Effectra\Database\DB  public function bindParam(int|string $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = null, mixed $driverOptions = null)
 * @method static \Effectra\Database\DB  public function bindMultipleParams(array $params): self
 * @method static \Effectra\Database\DB  public function fetch(): ?array
 * @method static \Effectra\Database\DB  public function fetchAsObject(): array|null
 * @method static \Effectra\Database\DB  public function fetchPretty(DataRulesInterface $rules): ?array
 * @method static \Effectra\Database\DB  public function fetchAsCollection(): ?DataCollectionInterface
 * @method static \Effectra\Database\DB  public function getDataInserted(): array
 * @method static \Effectra\Database\DB  public function setDataInserted(array $dataInserted): void
 * @method static \Effectra\Database\DB  public function data($data): self
 * @method static \Effectra\Database\DB  public function prettyData($data, DataRulesInterface  $rules)
 * @method static \Effectra\Database\DB  public function insert(): bool
 * @method static \Effectra\Database\DB  public function update(?Condition $conditions = null): bool
 * @method static \Effectra\Database\DB  public function deleteById(string|int $id, string $keyName = 'id'): bool
 * @method static \Effectra\Database\DB  public function transaction(callable $callback, array ...$args)
 */

class DB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseDB::class;
    }
}

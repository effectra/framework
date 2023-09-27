<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Database\DB as DatabaseDB;
use Effectra\Core\Facade;

/**
     * @method static \Effectra\Database\DB private function getConnection(): PDO
     * @method static \Effectra\Database\DB private function setConnection(PDO $connection): void
     * @method static \Effectra\Database\DB private function getQuery(): string
     * @method static \Effectra\Database\DB private function setQuery(string $query): void
     * @method static \Effectra\Database\DB public function query(string $query): self
     * @method static \Effectra\Database\DB private function getStatement(): PDOStatement|false
     * @method static \Effectra\Database\DB public function statement(): PDOStatement|false
     * @method static \Effectra\Database\DB private function hasStatement(): bool
     * @method static \Effectra\Database\DB private function setStatement(PDOStatement|false $statement): void
     * @method static \Effectra\Database\DB private function getTable(): string
     * @method static \Effectra\Database\DB private function setTable(string $table): void
     * @method static \Effectra\Database\DB private function isSetTableName()
     * @method static \Effectra\Database\DB public function table(string $table): self
     * @method static \Effectra\Database\DB public function beginTransaction(): bool
     * @method static \Effectra\Database\DB public function commit(): bool
     * @method static \Effectra\Database\DB public function quote(string $string, int $type = PDO::PARAM_STR): string|false
     * @method static \Effectra\Database\DB public function rollback(): bool
     * @method static \Effectra\Database\DB public function lastInsertId(): string|false
     * @method static \Effectra\Database\DB public function inTransaction(): bool
     * @method static \Effectra\Database\DB public function errorCode(): ?string
     * @method static \Effectra\Database\DB public function errorInfo(): array
     * @method static \Effectra\Database\DB public function exec(string $statement): int|false
     * @method static \Effectra\Database\DB public function getAttribute(int $attribute): mixed
     * @method static \Effectra\Database\DB public function getAvailableDrivers(): array
     * @method static \Effectra\Database\DB public function setAttribute(int $attribute, mixed $value): bool
     * @method static \Effectra\Database\DB public function run(?array $params = null): bool
     * @method static \Effectra\Database\DB public function bindParam(int|string $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = null, mixed $driverOptions = null)
     * @method static \Effectra\Database\DB public function bindMultipleParams(array $params): self
     * @method static \Effectra\Database\DB public function fetch(): array|null
     * @method static \Effectra\Database\DB public function fetchObject(): array|null
     * @method static \Effectra\Database\DB public function fetchPretty(callable $rules): ?array
     * @method static \Effectra\Database\DB public function getDataInserted(): array
     * @method static \Effectra\Database\DB public function setDataInserted(array $dataInserted): void
     * @method static \Effectra\Database\DB public function data($data)
     * @method static \Effectra\Database\DB public function prettyData($data, callable $rules)
     * @method static \Effectra\Database\DB public function validatePayload(array $payload, array $tableInfo): array
     * @method static \Effectra\Database\DB public function insert($data): bool
     * @method static \Effectra\Database\DB public function update(array $data, ?Condition $conditions = null): bool
     * @method static \Effectra\Database\DB public function delete($conditions, ?array $params = null): bool
 */

class DB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseDB::class;
    }
}

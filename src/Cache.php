<?php

declare(strict_types=1);

namespace Effectra\Core;

/**
 * @method get(string $key, mixed $default = null): mixed;
 * @method set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool;
 * @method delete(string $key): bool;
 * @method clear(): bool;
 * @method getMultiple(iterable $keys, mixed $default = null): iterable;
 * @method setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool;
 * @method deleteMultiple(iterable $keys): bool;
 * @method has(string $key): bool;
 */
class Cache
{
}

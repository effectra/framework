<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Core\Cache as CoreCache;
use Effectra\Core\Facade;

/**
    * @method static get(string $key, mixed $default = null): mixed;
    * @method static set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool;
    * @method static delete(string $key): bool;
    * @method static clear(): bool;
    * @method static getMultiple(iterable $keys, mixed $default = null): iterable;
    * @method static setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool;
    * @method static deleteMultiple(iterable $keys): bool;
    * @method static has(string $key): bool;
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CoreCache::class;
    }
}

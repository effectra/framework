<?php

declare(strict_types=1);

namespace Effectra\Core;

/**
 * The Cache class is a placeholder implementation for a cache system.
 *
 * @method mixed get(string $key, mixed $default = null)
 * @method bool set(string $key, mixed $value, null|int|\DateInterval $ttl = null)
 * @method bool delete(string $key)
 * @method bool clear()
 * @method iterable getMultiple(iterable $keys, mixed $default = null)
 * @method bool setMultiple(iterable $values, null|int|\DateInterval $ttl = null)
 * @method bool deleteMultiple(iterable $keys)
 * @method bool has(string $key)
 */
class Cache
{
    /**
     * Handles dynamic method calls.
     *
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @return mixed The result of the method call.
     */
    public function __call(string $name, array $arguments)
    {
        throw new \BadMethodCallException('Method ' . $name . '() is not implemented.');
    }
}

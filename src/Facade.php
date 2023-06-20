<?php

declare(strict_types=1);

namespace Effectra\Core;

use RuntimeException;

/**
 * Class Facade
 *
 * The Facade class provides a convenient way to access an underlying object
 * in a static manner, acting as a static proxy. It allows accessing methods
 * on the underlying object without explicitly creating an instance of it.
 */
class Facade
{
    /**
     * The underlying container instance.
     *
     * @var mixed
     */
    protected static $container;

    /**
     * Set the container instance.
     *
     * @param mixed $container The container instance.
     * @return void
     */
    public static function setContainer($container): void
    {
        static::$container = $container;
    }

    /**
     * Get the facade accessor.
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException("Facade does not implement getFacadeAccessor method.");
    }

    /**
     * Handle dynamic, static calls to the facade.
     *
     * @param string $method The method name.
     * @param array $args The method arguments.
     * @return mixed The result of the method call.
     *
     * @throws RuntimeException If the method does not exist.
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();
        
        if (!method_exists($instance, $method)) {
            throw new RuntimeException(sprintf('Method "%s" not found.', $method));
        }
        
        return $instance->$method(...$args);
    }

    /**
     * Get the instance of the underlying object.
     *
     * @return mixed The instance of the underlying object.
     */
    protected static function getInstance()
    {
        $instance = static::getFacadeAccessor();
        $accessor = Application::container()->get($instance);
        
        return $accessor;
    }
}

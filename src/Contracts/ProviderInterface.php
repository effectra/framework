<?php

namespace Effectra\Core\Contracts;

/**
 * Interface ProviderInterface
 *
 * Represents a service provider that registers services in a container.
 */
interface ProviderInterface
{
    /**
     * Binds a service name to a value or implementation.
     *
     * @param string $name The name of the service.
     * @param mixed $value The value or implementation of the service.
     * @return void
     */
    public function bind($name, $value);
}

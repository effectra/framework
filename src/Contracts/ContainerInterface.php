<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

use Psr\Container\ContainerInterface as ContainerContainerInterface;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
interface ContainerInterface extends ContainerContainerInterface
{

    /**
     * Define an object or a value in the container.
     *
     * @param string $name Entry name
     * @param mixed $value Value
     */
    public function set(string $name, mixed $value): void;
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Database;

/**
 * ModelCollection class represents a collection of data with array-related methods.
 */
class ModelCollection implements \Countable, \ArrayAccess
{
    /**
     * The underlying data array.
     *
     * @var array
     */
    private array $data;

    /**
     * Create a new ModelCollection instance.
     *
     * @param array $data The underlying data array.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Count the number of elements in the collection.
     *
     * @return int The number of elements in the collection.
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Check if an offset exists in the collection.
     *
     * @param mixed $offset The offset to check.
     *
     * @return bool true if the offset exists, false otherwise.
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the value at a specific offset in the collection.
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed The value at the specified offset.
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Set the value at a specific offset in the collection.
     *
     * @param mixed $offset The offset to set.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unset a specific offset in the collection.
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * Filter the collection using a callback function.
     *
     * @param callable $callback The callback function to use for filtering.
     *
     * @return ModelCollection A new ModelCollection containing the filtered elements.
     */
    public function filter(callable $callback): ModelCollection
    {
        return new ModelCollection(array_filter($this->data, $callback));
    }

    /**
     * Map the collection using a callback function.
     *
     * @param callable $callback The callback function to use for mapping.
     *
     * @return ModelCollection A new ModelCollection containing the mapped elements.
     */
    public function map(callable $callback): ModelCollection
    {
        return new ModelCollection(array_map($callback, $this->data));
    }

    /**
     * Invoke the collection to return the underlying data.
     *
     * @return array The underlying data array.
     */
    public function __invoke(): array
    {
        return $this->data;
    }
}

<?php

namespace Effectra\Core\Utils;

/**
 * The Collection class provides basic collection functionality for working with arrays.
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected array $items = [];

    /**
     * Create a new Collection instance.
     *
     * @param array $items The initial items of the collection.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all items from the collection.
     *
     * @return array The items in the collection.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key     The key of the item.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return mixed The item value if found, otherwise the default value.
     */
    public function get($key, $default = null)
    {
        return isset($this->items[$key]) ? $this->items[$key] : $default;
    }

    /**
     * Check if an item exists in the collection by key.
     *
     * @param mixed $key The key of the item.
     *
     * @return bool True if the item exists, false otherwise.
     */
    public function has($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Put an item into the collection.
     *
     * @param mixed $key   The key of the item.
     * @param mixed $value The value of the item.
     */
    public function put($key, $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param mixed $key The key of the item to remove.
     */
    public function forget($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Apply a callback function to each item in the collection and return a new collection.
     *
     * @param callable $callback The callback function to apply.
     *
     * @return static The new collection with the modified items.
     */
    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    /**
     * Filter the collection using a callback function and return a new collection.
     *
     * @param callable $callback The callback function to use for filtering.
     *
     * @return static The new collection with the filtered items.
     */
    public function filter(callable $callback): static
    {
        return new static(array_filter($this->items, $callback));
    }

    /**
     * Remove and return the last item from the collection.
     *
     * @return static The new collection with the last item removed.
     */
    public function pop()
    {
        return new static(array_pop($this->items));
    }

    /**
     * Returns an array containing only the input data for the specified input keys.
     * @param array $items An array of input keys to include in the result.
     * @return array
     */
    public function only(array $items): static
    {
        $arr = array_intersect_key((array) $items, array_flip($this->items));
        return new static($arr);
    }
    /**
     * Get the number of items in the collection.
     *
     * @return int The number of items in the collection.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Determine if an item exists in the collection using the ArrayAccess interface.
     *
     * @param mixed $offset The offset to check.
     *
     * @return bool True if the item exists, false otherwise.
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Get an item from the collection using the ArrayAccess interface.
     *
     * @param mixed $offset The offset of the item.
     *
     * @return mixed The item value if found, null otherwise.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set an item in the collection using the ArrayAccess interface.
     *
     * @param mixed $offset The offset of the item.
     * @param mixed $value  The value of the item.
     */
    public function offsetSet($offset, $value): void
    {
        $this->put($offset, $value);
    }

    /**
     * Unset an item from the collection using the ArrayAccess interface.
     *
     * @param mixed $offset The offset of the item to unset.
     */
    public function offsetUnset($offset): void
    {
        $this->forget($offset);
    }

    /**
     * Get an iterator for iterating over the collection using the IteratorAggregate interface.
     *
     * @return \Traversable An iterator object.
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core;

/**
 * Class Event
 *
 * A simple event dispatcher implementation for registering and dispatching events.
 */
class Event
{
    /**
     * Array to store event listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * Register a listener for a specific event.
     *
     * @param string $eventName The name of the event.
     * @param callable $callback The callback function to be executed when the event is dispatched.
     * @return void
     */
    public function listen(string $eventName, callable $callback): void
    {
        $this->listeners[$eventName][] = $callback;
    }

    /**
     * Dispatch an event with the given name and payload.
     *
     * @param string $eventName The name of the event to dispatch.
     * @param array $payload The optional payload to pass to the event listeners.
     * @return void
     */
    public function dispatch(string $eventName, array $payload = []): void
    {
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                call_user_func_array($listener, $payload);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Core\Contracts\ListenerProviderInterface;

class EventProvider
{

    private static array $events = [];

    public function __construct(private ListenerProviderInterface $provider)
    {
    }


    public static function set($event, array $listeners = []): void
    {
        self::$events[$event] = $listeners;
    }

    public function getEvents(): array
    {
        return self::$events;
    }

    public function register(): void
    {
        foreach ($this->getEvents() as $eventName => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->provider->addListener($eventName, new $listener());
            }
        }
    }
}

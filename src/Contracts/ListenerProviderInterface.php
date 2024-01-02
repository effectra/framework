<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

interface ListenerProviderInterface {

    public function addListener(string $eventType, callable $listener): void;
    public function getListenersForEvent(object $event): iterable;

}
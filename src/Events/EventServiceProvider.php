<?php

namespace Effectra\Core\Events;

use Effectra\Core\Authentication\EventListeners\SendUserLoggedNtfMailListener;
use Effectra\Core\Authentication\Events\UserLoggedEvent;
use Effectra\Core\EventListeners\AddRequestAndResponseToContainerListener;

class EventServiceProvider 
{
    private array $listen = [
        ResponseEvent::class => [
           AddRequestAndResponseToContainerListener::class
        ],
        UserLoggedEvent::class => [
            SendUserLoggedNtfMailListener::class
        ]
    ];

    
    
}

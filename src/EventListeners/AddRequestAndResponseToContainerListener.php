<?php

namespace Effectra\Core\EventListeners;

use Effectra\Core\Application;
use Effectra\Core\Events\ResponseEvent;
use Effectra\Core\Request;
use Effectra\Core\Response;

class AddRequestAndResponseToContainerListener
{
    public function __invoke(ResponseEvent $event): void
    {
        Application::container()->set(Request::class,$event->getRequest());
        Application::container()->set(Response::class,$event->getResponse());
        $event->stopPropagation();
    }
}
<?php

namespace Effectra\Core\Events;

use Effectra\Core\Request;
use Effectra\Core\Response;
use Effectra\EventDispatcher\Event;

class ResponseEvent extends Event
{
    public function __construct(
        private Request $request,
        private Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
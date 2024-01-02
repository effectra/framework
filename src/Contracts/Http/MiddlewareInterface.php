<?php

namespace Effectra\Core\Contracts\Http;

use Effectra\Http\Extensions\Contracts\RequestExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareInterface
{
   
    public function process(RequestExtensionInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}

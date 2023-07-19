<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use Effectra\Http\Server\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionStartMiddleware extends Middleware implements MiddlewareInterface
{
	
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if(!session()->isActive()){
			session()->start();
		}
		return $handler->handle($request);
	}
}

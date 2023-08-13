<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use Effectra\Http\Server\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Effectra\Session\Session;


class SessionStartMiddleware extends Middleware implements MiddlewareInterface
{
	public function __construct(protected Session $session) {
	}
	
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		 
		if(!$this->session->isActive()){
			$this->session->start();
		}
		return $handler->handle($request);
	}
}

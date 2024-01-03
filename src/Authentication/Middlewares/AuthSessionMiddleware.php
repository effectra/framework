<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthSessionMiddleware
 *
 * Middleware for handling session-based authentication.
 *
 * @package Effectra\Core\Authentication\Middlewares
 */
class AuthSessionMiddleware implements MiddlewareInterface
{
    /**
     * Process the middleware.
     *
     * @param ServerRequestInterface  $request  The request object.
     * @param RequestHandlerInterface $handler  The request handler.
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}

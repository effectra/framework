<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Http\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class VerifyEmailMiddleware
 *
 * Middleware for verifying the email status of a user.
 *
 * @package Effectra\Core\Authentication\Middlewares
 */
class VerifyEmailMiddleware implements MiddlewareInterface
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
        // Retrieve the user from the request attributes
        $user = $request->getAttribute('user');

        // If the user is verified, continue processing
        if ($user?->getVerifiedAt()) {
            return $handler->handle($request);
        }

        // If the user is not verified, redirect to the email verification endpoint
        return new RedirectResponse('api/auth/email/verify');
    }
}

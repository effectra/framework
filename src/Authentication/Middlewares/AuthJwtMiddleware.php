<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Authentication\Contracts\AuthInterface;
use Effectra\Core\Contracts\Http\MiddlewareInterface;
use Effectra\Core\Response;
use Effectra\Http\Extensions\Contracts\RequestExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthJwtMiddleware
 *
 * Middleware for handling JWT-based authentication.
 *
 * @package Effectra\Core\Authentication\Middlewares
 */
class AuthJwtMiddleware implements MiddlewareInterface
{
    /**
     * AuthJwtMiddleware constructor.
     *
     * @param AuthInterface $auth The authentication interface.
     */
    public function __construct(protected AuthInterface $auth)
    {
    }

    /**
     * Process the middleware.
     *
     * @param RequestExtensionInterface  $request  The request object.
     * @param RequestHandlerInterface    $handler  The request handler.
     *
     * @return ResponseInterface
     */
    public function process(RequestExtensionInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = (new Response())->json([
            'message' => 'Please log in'
        ], 400);

        $token = $request->getTokenFromBearer();

        if (!$token) {
            return $response;
        }

        $this->auth->setToken($token);

        if ($user = $this->auth->user()) {
            $request = $request->withAttribute('user', $user);
            $response = $handler->handle($request);

            return $response;
        }

        return $response;
    }
}

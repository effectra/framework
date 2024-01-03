<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Authentication\Models\User;
use Effectra\Core\Security\EncryptUrl;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class GetUserFromHashedUriMiddleware
 *
 * Middleware for retrieving a user based on a hashed URI.
 *
 * @package Effectra\Core\Authentication\Middlewares
 */
class GetUserFromHashedUriMiddleware implements MiddlewareInterface
{
    /**
     * Process the middleware.
     *
     * @param ServerRequestInterface  $request  The request object.
     * @param RequestHandlerInterface $handler  The request handler.
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Decrypt data from the hashed URI
        $data = (new EncryptUrl(env('APP_KEY')))->get($request->getUri()->withQuery(http_build_query($request->getQueryParams())));

        // Retrieve user based on the decrypted user ID
        $user = User::find($data['userId']);

        // Throw an exception if the user is not found
        if (!$user) {
            throw new \Exception("Error Processing User");
        }

        // Pass the user attribute to the request and continue processing
        return $handler->handle($request->withAttribute('user', $user));
    }
}

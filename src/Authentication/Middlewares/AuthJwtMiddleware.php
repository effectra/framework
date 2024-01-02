<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Authentication\Contracts\AuthInterface;
use Effectra\Core\Contracts\Http\MiddlewareInterface;
use Effectra\Core\Response;
use Effectra\Http\Extensions\Contracts\RequestExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthJwtMiddleware implements MiddlewareInterface
{
    public function __construct(protected AuthInterface $auth)
    {
    }

    public function process(RequestExtensionInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = (new Response())->json([
            'message' => 'please login'
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

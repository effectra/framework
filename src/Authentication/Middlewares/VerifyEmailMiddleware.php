<?php

declare(strict_types = 1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Http\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyEmailMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if ($user?->getVerifiedAt()) {
            return $handler->handle($request);
        }

        return new RedirectResponse('api/auth/email/verify');
    }
}

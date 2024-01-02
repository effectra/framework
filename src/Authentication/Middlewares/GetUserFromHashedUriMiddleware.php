<?php

declare(strict_types = 1);

namespace Effectra\Core\Authentication\Middlewares;

use Effectra\Core\Authentication\Models\User;
use Effectra\Core\Security\EncryptUrl;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetUserFromHashedUriMiddleware implements MiddlewareInterface
{
    

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $data = (new EncryptUrl(env('APP_KEY')))->get($request->getUri()->withQuery(http_build_query($request->getQueryParams())));
        $user = User::find($data['userId']);
        if(!$user){
            throw new \Exception("Error Processing User");
        }
        return $handler->handle($request->withAttribute('user',$user));
    }
}

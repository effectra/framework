<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use Effectra\Core\Cache;
use Effectra\Core\Request;
use Effectra\Core\Response;
use Effectra\Http\Server\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RateLimitMiddleware extends Middleware implements MiddlewareInterface
{
    public function __construct(protected Cache $cache, protected Response $response)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ex_request = Request::convertRequest($request);

        $clientIp = $ex_request->getClientIp([]);

        $cacheKey = 'rate_limit_' . $clientIp;

        $requests = (int) $this->cache->get($cacheKey);

        if ($requests > 3) {
            return $this->response->json([
                'message'=>'Too many request'
            ],429);
        }

        $this->cache->set($cacheKey, $requests + 1, 3600);

        return $handler->handle($request);
    }
}

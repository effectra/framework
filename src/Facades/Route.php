<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Router\Route as RouterRoute;


/**
 * @method static \Effectra\Router\Middleware middleware(string|MiddlewareInterface $middlewareClass): self
 * 
 * @method static \Effectra\Router\Utils group(string $common_route, $controller, array $methods): self
 * @method static \Effectra\Router\Utils crud(string $route, $controller, string $actions, ?MiddlewareInterface|array $middleware = null): self
 * @method static \Effectra\Router\Utils auth(string $pattern, $controller): self
 * 
 * @method static \Effectra\Router\Register setPreRoute(string $preRoute): void
 * @method static \Effectra\Router\Register get(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register post(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register put(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register delete(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register patch(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register options(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register any(string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register register(string $method, string $pattern, array|callable $callback): self
 * @method static \Effectra\Router\Register routes(): array
 * @method static \Effectra\Router\Register name(): self
 * @method static \Effectra\Router\Register getArguments(string $route_parts, string $pattern_parts): array
 * 
 * @method static\Effectra\Router\Dispatcher addArguments(array $args): void
 * @method static\Effectra\Router\Dispatcher addRequest(ServerRequestInterface $request): void
 * @method static\Effectra\Router\Dispatcher addResponse(ResponseInterface $response): void
 * @method static\Effectra\Router\Dispatcher setNotFound(callable $response): void
 * @method static\Effectra\Router\Dispatcher setInternalServerError(callable $response): void
 */

 class Route extends Facade 
{
    protected static function getFacadeAccessor()
    {
        return RouterRoute::class;
    }
}

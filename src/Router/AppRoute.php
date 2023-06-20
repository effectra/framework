<?php

declare(strict_types=1);

namespace Effectra\Core\Router;

use Effectra\Core\Application;
use Effectra\Fs\File;
use Effectra\Router\Route;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The AppRoute class manages routes for different types (API, web) in the application.
 */
class AppRoute
{
    protected array $routerTypes = ['api', 'web'];

    public function __construct(protected Route $router)
    {
    }

    /**
     * Get the underlying router instance.
     *
     * @return Route The router instance.
     */
    public function getRouter(): Route
    {
        return $this->router;
    }

    /**
     * Add a new route type.
     *
     * @param string $type The route type to add.
     */
    public function addRoutesType(string $type): void
    {
        if (!in_array($type, $this->routerTypes)) {
            $this->routerTypes[] = $type;
        }
    }

    /**
     * Set the request and response for the router.
     *
     * @param RequestInterface  $request  The request object.
     * @param ResponseInterface $response The response object.
     */
    public function set(RequestInterface $request, ResponseInterface $response): void
    {
        $this->router->addResponse($response);
        $this->router->addRequest($request);
    }

    /**
     * Load the routes for the specified type.
     *
     * @param string $type The route type to load.
     */
    public function getRoutes(string $type = 'web'): void
    {
        $file = Application::routesPath($type . '.php');
        if (File::exists($file)) {
            $routes = require $file;
            $routes($this->router);
        }
        if ($type === 'api') {
            $this->router->setPreRoute('api');
        }
    }

    /**
     * Register the routes for all types.
     */
    public function register(): void
    {
        foreach ($this->routerTypes as $type) {
            $this->getRoutes($type);
        }
    }

    /**
     * Handle the incoming request and return the response.
     *
     * @param RequestInterface $request The incoming request.
     *
     * @return ResponseInterface The response to send.
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        if (Application::isApiPath($request)) {
            $this->router->setNotFound(function () {
                return response()->json([
                    'message' => 'The requested resource was not found on this server.'
                ]);
            });
        }
        $response = $this->router->dispatch($request);

        return $response;
    }
}

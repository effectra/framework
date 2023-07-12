<?php

declare(strict_types=1);

namespace Effectra\Core\Router;

use Effectra\Core\Application;
use Effectra\Fs\File;
use Effectra\Http\Message\Uri;
use Effectra\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The AppRoute class manages routes for different types (API, web) in the application.
 */
class AppRoute
{
    /**
     * @var array $routerTypes define routes files 
     */
    protected array $routerTypes = ['api', 'web'];

    /** 
     * @param Route $router The router instance.
     * @return void
     */
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
     * @param ServerRequestInterface  $request  The request object.
     * @param ResponseInterface $response The response object.
     */
    public function set(ServerRequestInterface $request, ResponseInterface $response): void
    {
        $this->router->addRequest($request);
        $this->router->addResponse($response);
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
     * @param ServerRequestInterface $request The incoming request.
     *
     * @return ResponseInterface The response to send.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        
        if(method_exists($this->router,'setContainer')){
            $this->router->setContainer(Application::container());
        }

        if(isset($_ENV['APP_URL'])){
            $uri = new Uri($_ENV['APP_URL']);

            $requestUri = $request->getUri();
    
            $newPath = str_replace($uri->getPath(),'',$requestUri->getPath());
    
            if($newPath === ''){
                $newPath = '/';
            }

            $request = $request->withUri($uri->withPath($newPath));
        }


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

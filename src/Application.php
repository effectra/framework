<?php

declare(strict_types=1);

namespace Effectra\Core;

use App\AppCore;
use Effectra\Config\ConfigFile;
use Effectra\Core\Console\AppConsole;
use Effectra\Core\Contracts\ContainerInterface as ContractsContainerInterface;
use Effectra\Core\Error\AppError;
use Effectra\Core\Events\ResponseEvent;
use Effectra\Core\Log\AppLogger;
use Effectra\Core\Middlewares\AppMiddleware;
use Effectra\Core\Router\AppRoute;
use Effectra\Core\Server\DurationCalculator;
use Effectra\Fs\Path;
use Effectra\Http\Foundation\ResponseFoundation;
use Effectra\Http\Server\RequestHandler;
use Effectra\Log\Logger;
use Effectra\Router\Resolver;
use Effectra\Router\Route;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Application
 *
 * The core class of the Effectra application.
 */
class Application
{
    /**
     * The version of the application.
     */
    const VERSION = '2.3';

    /**
     * The application path.
     *
     * @var string
     */
    protected static $APP_PATH;

    /**
     * The container instance.
     *
     * @var ContainerInterface
     */
    protected static ContainerInterface $container;

    /**
     * The current request.
     *
     * @var ServerRequestInterface
     */
    protected static $request;

    /**
     * The current response.
     *
     * @var ResponseInterface
     */
    protected static $response;

    /**
     * The duration calculator for measuring the application execution time.
     *
     * @var DurationCalculator
     */
    protected $duration;

    protected $appError;

    /**
     * Application constructor.
     *
     * @param AppCore $appCore The AppCore instance.
     */
    public function __construct(protected AppCore $appCore)
    {
        //Calculate app running duration
        $this->duration = new DurationCalculator();
        $this->duration->start();
    }

    /**
     * Set the application path.
     *
     * @param string $path The application path.
     */
    public function setAppPath(string $path)
    {
        static::$APP_PATH = $path;
    }

    /**
     * Get the app configuration.
     *
     * @return array The app configuration.
     */
    public static function getConfig()
    {
        $file = Application::configPath('app.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    public static function getLang(): string
    {
        return static::getConfig()['lang'] ?? 'en';
    }

    /**
     * Get the container instance.
     *
     * @return ContainerInterface|ContractsContainerInterface The container instance.
     */
    public static function container(): ContainerInterface|ContractsContainerInterface
    {
        return static::$container;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.

     * @param string $id Identifier of the entry to look for.

     * @return mixed Entry.

     * @throws NotFoundExceptionInterface No entry was found for this identifier.

     * @throws ContainerExceptionInterface Error while retrieving the entry.
     * 
     */
    public static function get($id)
    {
        static::container()->get($id);
    }

    /**
     * Define an object or a value in the container.
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set(string $name, mixed $value)
    {
        $c =  static::container()->set($name, $value);
        static::$container = $c;
    }

    /**
     * Set the container instance.
     *
     * @param ContainerInterface $container The container instance.
     */
    public function setContainer(ContainerInterface $container): void
    {
        static::$container = $container;
        Resolver::setContainer(static::container());
    }

    /**
     * Get the logger instance.
     *
     * @return LoggerInterface The logger instance.
     */
    public static function log(): LoggerInterface
    {
        //create log file
        if (!AppLogger::exists()) {
            AppLogger::create();
        }
        return static::container()->get(Logger::class);
    }

    /**
     * Get the application path.
     *
     * @param string $path The additional path within the application directory.
     * @return string The full path.
     */
    public static function appPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . $path;
    }

    /**
     * Get the configuration path.
     *
     * @param string $path The additional path within the configuration directory.
     * @return string The full path.
     */
    public static function configPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'config' . Path::ds() . $path;
    }

    /**
     * Get the routes path.
     *
     * @param string $path The additional path within the routes directory.
     * @return string The full path.
     */
    public static function routesPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'routes' . Path::ds() . $path;
    }

    /**
     * Get the view path.
     *
     * @param string $path The additional path within the view directory.
     * @return string The full path.
     */
    public static function viewPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'view' . Path::ds() . $path;
    }

    /**
     * Get the database path.
     *
     * @param string $path The additional path within the database directory.
     * @return string The full path.
     */
    public static function databasePath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'database' . Path::ds() . $path;
    }

    /**
     * Get the public path.
     *
     * @param string $path The additional path within the public directory.
     * @return string The full path.
     */
    public static function publicPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'public' . Path::ds() . $path;
    }

    /**
     * Get the resources path.
     *
     * @param string $path The additional path within the resources directory.
     * @return string The full path.
     */
    public static function resourcesPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'resources' . Path::ds() . $path;
    }

    /**
     * Get the storage path.
     *
     * @param string $path The additional path within the storage directory.
     * @return string The full path.
     */
    public static function storagePath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'storage' . Path::ds() . $path;
    }

    /**
     * Get the tests path.
     *
     * @param string $path The additional path within the tests directory.
     * @return string The full path.
     */
    public static function testsPath(string $path = ''): string
    {
        return static::$APP_PATH . Path::ds() . 'tests' . Path::ds() . $path;
    }

    /**
     * Get the middlewares based on the specified type.
     *
     * @param string $type The type of middlewares to retrieve ('web' or 'api').
     * @return array The middleware instances.
     */
    public function getMiddlewares(string $type = 'web'): array
    {
        $middlewares = AppMiddleware::get($type) + $this->appCore->middlewares[$type];

        $middlewaresInstant =  array_map(function ($middleware) {
            return Resolver::resolveClass($middleware);
        }, $middlewares);

        return $middlewaresInstant;
    }

    /**
     * Check if the given request path represents an API path.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return bool True if the path contains 'api/', false otherwise.
     */
    public static function isApiPath(ServerRequestInterface $request): bool
    {
        $path = $request->getUri()->getPath();
        return (bool) strpos($path, 'api/');
    }


    /**
     * get app Event Dispatcher
     * @return EventDispatcherInterface
     */
    public static function eventDispatcher(): EventDispatcherInterface
    {
        return static::container()->get(EventDispatcherInterface::class);
    }

    /**
     * Handle the incoming HTTP request.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface The response generated by the application.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->appError = new AppError();
        $this->appError->setLogger($this->log());

        $typeEndpoint = $this->isApiPath($request) ? 'api' : 'web';

        $this->appError->handle($typeEndpoint);

        $router = new AppRoute(new Route(), $typeEndpoint);

        if ($typeEndpoint === 'web') {

            $middlewares = $this->getMiddlewares();
        } else {
            $middlewares = $this->getMiddlewares('api');
        }

        $response = new Response();

        $request = Request::convertRequest($request);

        $handler = new RequestHandler($response, $middlewares);

        $response = $handler->handle($request);


        $request = $router->rebuildRequestUri($request);

        $router->set(
            $request,
            $response
        );

        $router->register();

        $response = $router->handle($request);

        static::container()->set(Route::class, $router->getRouter());

        $this->eventDispatcher()->dispatch(new ResponseEvent($request, $response));

        return $response;
    }

    /**
     * Run the application in console mode.
     */
    public function console(): void
    {
        $this->appError = new AppError($this->log());

        $this->appError->handle('cli');

        $console = new AppConsole();

        $console->execute();
    }

    /**
     * Run the application and send the response.
     *
     * @param ResponseInterface $response The response to send.
     */
    public function run(ResponseInterface $response): void
    {
        ResponseFoundation::send($response);
        $this->duration->stop();
    }
}

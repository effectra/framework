<?php

declare(strict_types=1);

namespace Effectra\Core;

use App\AppCore;
use DI\Container;
use Effectra\Config\ConfigFile;
use Effectra\Core\Console\AppConsole;
use Effectra\Core\Error\AppError;
use Effectra\Core\Http\Cors;
use Effectra\Core\Router\AppRoute;
use Effectra\Core\Server\DurationCalculator;
use Effectra\Fs\Path;
use Effectra\Http\Foundation\ResponseFoundation;
use Effectra\Http\Server\RequestHandler;
use Effectra\Log\Logger;
use Effectra\Router\Route;
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
    const VERSION = '1.0.0';

    /**
     * The application path.
     *
     * @var string
     */
    protected static $APP_PATH;

    /**
     * The container instance.
     *
     * @var Container
     */
    protected static Container $container;

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

    /**
     * Application constructor.
     *
     * @param AppCore $appCore The AppCore instance.
     */
    public function __construct(protected AppCore $appCore)
    {
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
    private static function getConfig()
    {
        $file = Application::configPath('app.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    public static function getLang() : string {
        return static::getConfig()['lang'] ?? 'en';
    }

    /**
     * Get the container instance.
     *
     * @return Container The container instance.
     */
    public static function container(): Container
    {
        return static::$container;
    }

    /**
     * Set the container instance.
     *
     * @param Container $container The container instance.
     */
    public function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    /**
     * Get the logger instance.
     *
     * @return LoggerInterface The logger instance.
     */
    public static function log(): LoggerInterface
    {
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
        return array_map(fn ($middleware) => new $middleware, $this->appCore->middlewares[$type]);
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
     * Handle the incoming HTTP request.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface The response generated by the application.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        AppError::endpoint($request);

        // Handle Errors
        AppError::handler();

        // Handle Middlewares
        $middlewares = $this->getMiddlewares();

        if ($this->isApiPath($request)) {
            $middlewares = $this->getMiddlewares('api');
        }

        $response = new Response();

        $request = Request::convertRequest($request);

        $handler = new RequestHandler($response, $middlewares);

        $response = $handler->handle($request);

        // Handle Router
        $router = new AppRoute(new Route());

        // Set Request & Response for controller
        $router->set(
            $request,
            $response
        );

        $router->register();

        $response = $router->handle($request);

        $response = Cors::process($request, $response);

        static::container()->set(Route::class, $router->getRouter());

        return $response;
    }

    /**
     * Run the application in console mode.
     */
    public function console(): void
    {
        // Handle Errors
        AppError::handler();

        $console = new AppConsole();

        $console->execute();
    }

    /**
     * Capture the current request and response.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @param ResponseInterface $response The response instance.
     */
    public function capture(ServerRequestInterface $request, ResponseInterface $response): void
    {
        static::$request = $request;
        static::$response = $response;
    }

    /**
     * Get the captured request and response.
     *
     * @return object The captured request and response as an object.
     */
    public static function getCaptures(): object
    {
        return (object) [
            'path' => static::$APP_PATH,
            'request' =>  static::$response,
            'response' =>  static::$response,
        ];
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

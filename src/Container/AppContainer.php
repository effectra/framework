<?php

namespace Effectra\Core\Container;

use DI\Container;
use DI\ContainerBuilder;
use Effectra\Core\Application;
use Effectra\Core\Container\Provider;
use Effectra\Fs\Directory;
use Effectra\Fs\Path;

/**
 * The application container for managing dependency injection and service providers.
 */
class AppContainer
{
    /**
     * The DI container instance.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The DI container builder instance.
     *
     * @var ContainerBuilder
     */
    protected ContainerBuilder $containerBuilder;

    /**
     * The container bindings.
     *
     * @var array
     */
    protected array $containerBindings = [];

    /**
     * The path to the application.
     *
     * @var string
     */
    protected string $APP_PATH;


    /**
     * Create a new instance of the AppContainer class.
     */
    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
        
    }

    /**
     * Bind a service to the container.
     *
     * @param mixed $service The service to bind.
     * @return void
     */
    public function bind($service): void
    {
        $this->containerBindings[] = $service;
    }

    /**
     * Set the application path.
     *
     * @param string $path The application path.
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->APP_PATH = $path;
    }

    /**
     * Build the container by registering providers and adding definitions.
     *
     * @return void
     */
    public function build(): void
    {
        $cache_dir = Application::appPath('bootstrap' . Path::ds() . 'cache' .  Path::ds() );
        
        $this->containerBuilder->enableCompilation($cache_dir);
        $this->containerBuilder->writeProxiesToFile(true, $cache_dir . '/proxies');


        $this->registerProviders();

        $this->containerBuilder->addDefinitions($this->containerBindings);

        $this->container = $this->containerBuilder->build();
    }

    /**
     * Get a service from the container.
     *
     * @param string $service The service to retrieve.
     * @return mixed The resolved service.
     */
    public function get(string $service)
    {
        return $this->container()->get($service);
    }

    /**
     * Get the services bound to the container.
     *
     * @return array The container bindings.
     */
    public function getServices(): array
    {
        return $this->containerBindings;
    }

    /**
     * Register providers by adding their services to the container bindings.
     *
     * @return void
     */
    private function registerProviders(): void
    {
        $services = $this->getProviderServices();
        $provider = new Provider();
        foreach ($services as $service) {
            $class = new $service;
            $class->register($provider);
        }
        $this->containerBindings = $provider->getServices();
    }

    /**
     * Get the services provided by the application's providers.
     *
     * @return array The provider services.
     */
    private function getProviderServices(): array
    {
        $appConfig = require $this->APP_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . "app.php";
        return $appConfig['providers'];
    }

    /**
     * Get the container instance.
     *
     * @return Container The container instance.
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Bind application classes to the container.
     *
     * @return void
     */
    public function bindAppClasses(): void
    {
        $controllers = Application::appPath('Controllers');
        foreach (Directory::files($controllers) as $controller) {
            $this->bind([$controller::class => new $controller()]);
        }
    }
}

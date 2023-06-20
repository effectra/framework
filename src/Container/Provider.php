<?php

namespace Effectra\Core\Container;

use Effectra\Core\Contracts\ProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class Provider
 *
 * Implementation of the ProviderInterface.
 * Registers and provides services to the container.
 */
class Provider implements ProviderInterface
{
    /**
     * The registered services.
     *
     * @var array
     */
    protected $registeredServices = [];

    /**
     * The container instance.
     *
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * Sets the container instance.
     *
     * @param ContainerInterface $container The container instance.
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * Retrieves a service from the container.
     *
     * @param string $id The identifier of the service.
     * @return mixed The requested service.
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Binds a service to the provider.
     *
     * @param string $name The name of the service.
     * @param mixed $value The value of the service.
     */
    public function bind($name, $value)
    {
        $this->registeredServices[$name] = $value;
    }

    /**
     * Retrieves the registered services.
     *
     * @return array The registered services.
     */
    public function getServices()
    {
        return $this->registeredServices;
    }
}

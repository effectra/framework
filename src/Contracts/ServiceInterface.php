<?php

namespace Effectra\Core\Contracts;

/**
 * Interface ServiceInterface
 *
 * Represents a service that can be registered with a service provider.
 */
interface ServiceInterface
{
    /**
     * Registers the service with the given provider.
     *
     * @param ProviderInterface $provider The provider to register the service with.
     * @return void
     */
    public function register(ProviderInterface $provider);
}

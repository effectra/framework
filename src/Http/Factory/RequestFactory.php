<?php

namespace Effectra\Core\Http\Factory;

use Effectra\Core\Request;
use Effectra\Http\Extensions\Contracts\RequestExtensionInterface;
use Effectra\Http\Message\Uri;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * RequestFactory creates instances of PSR-7 RequestInterface.
 *
 * This factory implements the Psr\Http\Message\RequestFactoryInterface
 * and provides a method to create RequestInterface instances.
 */
class RequestFactory implements RequestFactoryInterface
{
    /**
     * Creates a new HTTP request.
     *
     * @param string $method The HTTP method
     * @param UriInterface|string $uri The URI or URI string
     * @return RequestInterface The created RequestInterface instance
     */
    public function createRequest(string $method, $uri): RequestExtensionInterface
    {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        }

        return new Request($method, $uri);
    }
}

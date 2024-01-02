<?php

namespace Effectra\Core\Http\Factory;

use Effectra\Core\Response;
use Effectra\Http\Extensions\Contracts\ResponseExtensionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * ResponseFactory creates instances of PSR-7 ResponseInterface.
 *
 * This factory implements the Psr\Http\Message\ResponseFactoryInterface
 * and provides a method to create ResponseInterface instances.
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Creates a new response.
     *
     * @param int $code The HTTP status code (optional, default: 200)
     * @param string $reasonPhrase The reason phrase associated with the status code (optional)
     * @return ResponseInterface The created ResponseInterface instance
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseExtensionInterface
    {
        $response = new Response();
        $response = $response->withStatus($code, $reasonPhrase);
        return $response;
    }
}

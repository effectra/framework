<?php

declare(strict_types=1);

namespace Effectra\Core\Http;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The Cors class handles CORS (Cross-Origin Resource Sharing) headers for HTTP requests.
 */
class Cors
{
    /**
     * Get the CORS configuration from the application's configuration file.
     *
     * @return array The CORS configuration.
     */
    private static function getConfig(): array
    {
        $file = Application::configPath('cors.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    /**
     * Process the CORS headers for the given request and response.
     *
     * @param ServerRequestInterface $request  The HTTP request.
     * @param ResponseInterface      $response The HTTP response.
     *
     * @return ResponseInterface The updated HTTP response with CORS headers.
     */
    public static function process(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cors = static::getConfig();

        // Get headers from request
        $headers = $request->getHeaderLine('Access-Control-Request-Headers');

        // Set headers for response
        $response = $response->withHeader('Access-Control-Max-Age', (string) $cors['max-age']);
        $response = $response->withHeader('Access-Control-Allow-Origin', $cors['allow-origin']);
        $response = $response->withHeader('Access-Control-Allow-Credentials', $cors['allow-credentials'] ? 'true' : 'false');
        $response = $response->withHeader('Access-Control-Allow-Headers', $headers);
        $response = $response->withHeader('Access-Control-Allow-Methods', join(',', $cors['methods']));

        return $response;
    }
}

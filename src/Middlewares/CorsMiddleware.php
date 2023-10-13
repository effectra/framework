<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The Cors class handles CORS (Cross-Origin Resource Sharing) headers for HTTP requests.
 */
class CorsMiddleware
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
        $origin = $request->getHeaderLine('Origin');
        $headers = $request->getHeaderLine('Access-Control-Request-Headers');

        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
            $response = $response->withHeader('Access-Control-Allow-Methods', join(',', $cors['methods']));
            $response = $response->withHeader('Access-Control-Allow-Headers', $headers);
            $response = $response->withHeader('Access-Control-Max-Age', (string) $cors['max-age']);
            $response = $response->withStatus($cors['optionsSuccessStatus']);
            return $response;
        }

        // Regular request, set CORS headers
        if (in_array($origin, $cors['allow-origin'])) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
        }

        $response = $response->withHeader('Access-Control-Allow-Credentials', $cors['allow-credentials'] ? 'true' : 'false');
        $response = $response->withHeader('Vary', 'Origin');
        return $response;
    }
}

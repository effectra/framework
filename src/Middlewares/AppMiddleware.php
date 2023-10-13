<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

/**
 * Class AppMiddleware
 *
 * The class that provides a list of middlewares for different application types.
 */
class AppMiddleware
{
    /**
     * Get the list of middlewares based on the specified type.
     *
     * @param string|null $type The application type (e.g., "web" or "api").
     * @return array The list of middlewares for the specified type, or all middlewares if no type is specified.
     */
    public static function get(string $type = null)
    {
        $middlewares = [
            'web' => [
                 SessionStartMiddleware::class,
                 CsrfFieldsMiddleware::class,
            ],
            'api' => []
        ];

        return $type ? $middlewares[$type] : $middlewares;
    }
}

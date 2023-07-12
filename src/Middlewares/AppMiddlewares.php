<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use App\Middlewares\CsrfFieldsMiddleware;
/**
 * Class AppMiddlewares
 *
 * The class that provides a list of middlewares for different application types.
 */
class AppMiddlewares
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
                new SessionStartMiddleware(),
                new CsrfFieldsMiddleware(),
            ],
            'api' => []
        ];

        return $type ? $middlewares[$type] : $middlewares;
    }
    
}

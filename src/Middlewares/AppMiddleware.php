<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use App\Middlewares\CsrfFieldsMiddleware;

class AppMiddlewares
{

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

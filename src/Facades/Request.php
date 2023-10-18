<?php

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Core\Application;
use Effectra\Facade;


class Request extends Facade
{
    protected static function getFacadeAccessor()
    {
        static::setContainer(Application::container());
        return '\Effectra\Core\Request';
    }
}

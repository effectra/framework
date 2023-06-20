<?php

namespace Effectra\Core\Facades;

use Effectra\Core\Application;
use Effectra\Facade;


class AppFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        static::setContainer(Application::container());
        return '\Effectra\Core\Application';
    }
}

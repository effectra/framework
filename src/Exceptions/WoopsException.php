<?php

namespace Effectra\Core\Exceptions;

/**
 * The WoopsException class handles the registration of the Whoops error handler.
 */
class WoopsException
{
    /**
     * Register the Whoops error handler.
     *
     * @return void
     */
    public static function handle()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}

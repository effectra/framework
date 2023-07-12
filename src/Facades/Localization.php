<?php

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Core\Localization as CoreLocalization;

/** 
 * @method static \Effectra\Core\Localization translate(string $key) :mixed
 */

class Localization extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CoreLocalization::class;
    }
}

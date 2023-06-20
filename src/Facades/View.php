<?php 

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Core\View as CoreView;

/** @method static \Effectra\Core\View render(string $view, $data = [])  
 */

class View extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CoreView::class;
    }
}
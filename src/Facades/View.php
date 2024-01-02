<?php 

declare(strict_types=1);

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Core\View as CoreView;

/** @method static \Effectra\Core\View render(string $view, $data = []):self
 */

class View extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CoreView::class;
    }
}
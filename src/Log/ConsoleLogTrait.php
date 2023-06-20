<?php

declare(strict_types=1);

namespace Effectra\Core\Log;

use Effectra\Core\Application;

trait ConsoleLogTrait
{
    public function __invoke()
    {
        Application::log()->info(self::class . ' Class running in console');
    }
}
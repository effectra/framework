<?php

declare(strict_types=1);

namespace Effectra\Core\Log;

use Effectra\Core\Application;
/**
 * Trait for logging console activity.
 */
trait ConsoleLogTrait
{
    /**
     * Logs a message indicating that the class is running in console.
     *
     * @return void
     */
    public function __invoke()
    {
        Application::log()->info(self::class . ' Class running in console');
    }
}
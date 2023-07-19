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
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        Application::log()->info(self::class . ' Class running in console');
    }
}
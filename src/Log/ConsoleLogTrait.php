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
    public function log(string $cmd,string $classFile)
    {
        Application::log()->notice('running command ['.$cmd .'] from ' . $classFile);
    }
    
}
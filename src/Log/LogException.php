<?php

declare(strict_types=1);

namespace Effectra\Log\Utils;

use Effectra\Core\Application;
use Exception;

class LogException 
{
    public static function log(Exception $exception)
    {
        Application::log()->error('Exception occurred:');
        Application::log()->error('Message: ' . $exception->getMessage());
        Application::log()->error('File: ' . $exception->getFile());
        Application::log()->error('Line: ' . $exception->getLine());
        Application::log()->error('Stack trace: ' . PHP_EOL . $exception->getTraceAsString());
    }
}
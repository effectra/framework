<?php

declare(strict_types=1);

namespace Effectra\Core\Log;

use Effectra\Core\Application;
use Effectra\Fs\File;
use Effectra\Fs\Path;

/**
 * Class AppLogger
 *
 * Utility class for managing application log files.
 *
 * @package Effectra\Log\Utils
 */
class AppLogger
{
     /**
     * Get the path to the log file.
     *
     * @return string The path to the log file.
     */
    public static function logFile(): string
    {
        $filename = strtolower($_ENV['APP_NAME'] ?? 'effectra');
        return Application::storagePath("logs" . Path::ds() . "$filename.log");
    }

    /**
     * Check if the log file exists.
     *
     * @return bool True if the log file exists, false otherwise.
     */
    public static function exists(): bool
    {
        return File::exists(static::logFile());
    }

    /**
     * Create a new log file.
     *
     * @return bool True if the log file was successfully created, false otherwise.
     */
    public static function create(): bool
    {
        return File::put(static::logFile(), '');
    }

    /**
     * Clear the contents of the log file.
     *
     * @return bool True if the log file was successfully cleared, false otherwise.
     */
    public static function clear(): bool
    {
        return static::create();
    }

    /**
     * Delete the log file.
     *
     * @return bool True if the log file was successfully deleted, false otherwise.
     */
    public static function delete(): bool
    {
        return File::delete(static::logFile());
    }
}

<?php

namespace Effectra\Core\Error;

use Effectra\Core\Application;

/**
 * The ErrorLogger class handles logging of errors and exceptions.
 */
class ErrorLogger
{
    /**
     * Register the error and exception handlers.
     *
     * @return void
     */
    public function register()
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Handle PHP errors.
     *
     * @param int $errno The error number.
     * @param string $errstr The error message.
     * @param string $errfile The file where the error occurred.
     * @param int $errline The line number where the error occurred.
     * @return void
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $errorMessage = sprintf(
            "[%s] %s in %s on line %d",
            $this->getErrorType($errno),
            $errstr,
            $errfile,
            $errline
        );
        $this->log($errorMessage);
    }

    /**
     * Handle exceptions.
     *
     * @param \Exception|\Throwable $exception The exception object.
     * @return void
     */
    public function handleException($exception)
    {
        $errorMessage = sprintf(
            "[%s] %s in %s on line %d",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        $this->log($errorMessage);
    }

    /**
     * Get the error type based on the error number.
     *
     * @param int $errno The error number.
     * @return string The error type.
     */
    private function getErrorType($errno)
    {
        $errorTypes = [
            E_ERROR             => 'Fatal error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parse error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core error',
            E_CORE_WARNING      => 'Core warning',
            E_COMPILE_ERROR     => 'Compile error',
            E_COMPILE_WARNING   => 'Compile warning',
            E_USER_ERROR        => 'User error',
            E_USER_WARNING      => 'User warning',
            E_USER_NOTICE       => 'User notice',
            E_STRICT            => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User deprecated',
        ];

        return $errorTypes[$errno] ?? 'Unknown error';
    }

    /**
     * Log the error message.
     *
     * @param string $message The error message to log.
     * @return void
     */
    private function log($message)
    {
        return Application::log()->error($message);
    }
}

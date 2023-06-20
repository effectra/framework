<?php

namespace Effectra\Core\Error;

use Effectra\Core\Response;

/**
 * The ApiError class handles errors and exceptions in an API context.
 */
class ApiError
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
     * @param string $errfile The file in which the error occurred.
     * @param int $errline The line number where the error occurred.
     * @return void
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $errorData = [
            'type' => $this->getErrorType($errno) ?? 'Unknown error',
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ];

        $this->response($errorData);
    }

    /**
     * Handle PHP exceptions.
     *
     * @param \Exception $exception The exception object.
     * @return void
     */
    public function handleException($exception)
    {
        $errorData = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];

        $this->response($errorData);
    }

    /**
     * Get the error type based on the error number.
     *
     * @param int $errno The error number.
     * @return string|null The error type.
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
     * Send a JSON response with the error message.
     *
     * @param mixed $message The error message or data.
     * @return \Effectra\Core\Response The response object.
     */
    private function response($message)
    {
        return (new Response())->json($message);
    }
}

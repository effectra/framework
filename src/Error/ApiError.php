<?php

namespace Effectra\Core\Error;

use Effectra\Core\Response;
use Effectra\Http\Foundation\ResponseFoundation;
use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * The ApiError class handles errors and exceptions in an API context.
 */
class ApiError
{
    public ?ResponseInterface $errorResponse = null;
    public array $errors = [];

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
     * Send the error response.
     *
     * @return void
     */
    private function response()
    {
        $response = (new Response())->json(array_reverse($this->errors));
        ResponseFoundation::send($response);
    }

    /**
     * Record the error message.
     *
     * @param string $message The error message.
     * @return void
     */
    private function record($message)
    {
        $this->errors[] = $message;
    }

    /**
     * Get the error response.
     *
     * @return ResponseInterface|null The error response.
     */
    public function getErrorResponse()
    {
        return $this->errorResponse;
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

        $this->record($errorData);
    }

    /**
     * Handle PHP exceptions.
     *
     * @param \Exception $exception The exception object.
     * @return void
     */
    public function handleException(Exception $exception):void
    {
        $names = explode('\\', get_class($exception));
        $type = end($names);
        $errorData = [
            'type' => $type,
            'exception_class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];

        $this->record($errorData);
        $this->response();
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
}

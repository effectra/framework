<?php

declare(strict_types=1);

namespace Effectra\Core\Error;

/**
 * Class ErrorRegister
 *
 * The ErrorRegister class manages the registration and retrieval of custom error handlers.
 */
class ErrorRegister
{
    /**
     * @var array The array of registered error handlers.
     */
    protected array $errors  = [];

    /**
     * Bind a custom error handler to a specific error class.
     *
     * @param string $errorClass The class of the error to handle.
     * @param \Closure $callback The callback function to handle the error.
     * @return $this
     */
    public function bind(string $errorClass, \Closure $callback): self
    {
        $this->errors[$errorClass] = $callback;
        return $this;
    }

    /**
     * Get the array of registered error handlers.
     *
     * @return array The array of error handlers.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set the array of error handlers.
     *
     * @param array $errors The array of error handlers to set.
     * @return $this
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Exceptions;

use Throwable;

/**
 * Class ValidationException
 *
 * Exception thrown when there are validation errors.
 *
 * @package Effectra\Core\Authentication\Exceptions
 */
class ValidationException extends \RuntimeException
{
    /**
     * ValidationException constructor.
     *
     * @param array         $errors   The validation errors.
     * @param string        $message  The exception message.
     * @param int           $code     The exception code.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(public array $errors, string $message = 'Validation Error(s)', int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

declare(strict_types=1);

namespace Effectra\Core\Error;



/**
 * Class ErrorDisplayed
 *
 * Handles the display and handling of errors in different contexts (HTTP, CLI).
 */
class ErrorViewer
{
    /**
     * ErrorDisplayed constructor.
     *
     * @param mixed $error The error object.
     * @param bool $displayErrors Whether to display errors.
     * @param string $endpoint The endpoint context (e.g., 'web', 'cli', 'api').
     * @param LoggerInterface|null $logger The logger instance.
     * @param ErrorRegister $registeredErrors The registered errors for custom handling.
     */

    public function __construct(
        protected  $error,
        protected bool $displayErrors = true,
        protected string $endpoint,
        protected ?LoggerInterface $logger = null,
        protected ErrorRegister $registeredErrors
    ) {
    }

}

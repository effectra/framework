<?php

namespace Effectra\Core\Exceptions;

class ExpiredTimeException extends \Exception
{
    private int $statusCode;

    public function __construct($message = "Item has expired", $statusCode = 400, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

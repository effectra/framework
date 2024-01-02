<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

use Psr\Http\Message\ResponseInterface;

interface ErrorHandlerInterface
{
    public function process();
    public function ApiResponse();
    public function CliExecute();
    public function logError();
    public function handle();
}

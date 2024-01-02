<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Http\Extensions\RequestExtension;
use Effectra\Http\Foundation\RequestFoundation;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Request
 *
 * Represents an HTTP request with additional functionality provided by RequestExtension.
 */
class Request extends RequestExtension
{
    /**
     * @return ServerRequestInterface Creates a ServerRequestInterface instance from global variables.
     */
    public static function fromGlobal(): ServerRequestInterface
    {
        return RequestFoundation::createFromGlobals();
    }

    /**
     * @param ServerRequestInterface $request Server Request instance
     * @return self
     */
    public static function convertRequest(ServerRequestInterface $request): self
    {
        $new = new self(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion(),
            $request->getQueryParams(),
            $request->getParsedBody(),
            $request->getAttributes()
        );
        $new = $new->withUploadedFiles($request->getUploadedFiles());
        $new = $new->withCookieParams($request->getCookieParams());
        return $new;
    }

    /**
     * Validates input data using a third-party validation library.
     *
     * @return Validator A Validator object representing the validation results.
     */
    public function validateInputs(): Validator
    {

        return new Validator($this->data());
    }
}

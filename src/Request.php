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
     * @return static
     */
    public static function convertRequest(ServerRequestInterface $request): static
    {
        $new = new static(
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
        return $new;
    }

    /**
     * Returns an object containing all input data, including query params,
     * POST data, and input stream data.
     *
     * @return object An object of input data.
     */
    public function inputs(): object
    {
        $request = $this->convertRequest(static::fromGlobal());

        // Get query params
        $getParams = $request->getQueryParams();

        // Get POST data
        $postParams = $request->getParsedBody();

        // Get input stream data
        $inputData = $request->parseJsonFromBody() ?? []; 

        // Merge all params into a single array
        $params = array_merge($getParams, $postParams, $inputData);

        return (object) $params;
    }

    /**
     * Validates input data using a third-party validation library.
     *
     * @return Validator A Validator object representing the validation results.
     */
    public function validateInputs(): Validator
    {
        $data = $this->inputs();
        return new Validator($data);
    }
}

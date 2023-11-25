<?php

namespace Effectra\Core\Http;

use Effectra\Http\Message\Response;

class RedirectResponse extends Response
{
    public function __construct(string $url, int $statusCode = 302)
    {
        parent::__construct($statusCode, ['location' => $url]);
    }

}
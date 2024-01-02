<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Urls;

use Effectra\Core\Authentication\AuthHandler;
use Effectra\Core\Security\EncryptUrl;
use Psr\Http\Message\UriInterface;

class VerifyUrl
{
    public function create($data): UriInterface
    {
        return (new EncryptUrl(AuthHandler::secretKey()))->set(
            $data,
            AuthHandler::expirationLinkTime(),
            AuthHandler::verifyLink()
        );
    }
}

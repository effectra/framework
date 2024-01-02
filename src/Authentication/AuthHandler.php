<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

class AuthHandler
{

    public static function secretKey()
    {
        return $_ENV['APP_KEY'];
    }

    public static function expirationLinkTime($time = 1800): \Datetime
    {
        return (new \Datetime())->setTimestamp(time() + $time);
    }

    public static function verifyLink()
    {
        return trim($_ENV['APP_URL'], '/') . '/api/auth/email/verify';
    }

    public static function forgetPasswordLink()
    {
        return trim($_ENV['APP_URL'], '/') . '/api/auth/email/verify';
    }
}

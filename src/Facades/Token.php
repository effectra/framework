<?php

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Security\Token as SecurityToken;

/** 
 * @method static Effectra\Security\Token config(object $config):self
 * @method static Effectra\Security\Token issuedAt($time):self
 * @method static Effectra\Security\Token expirationTime($time):self
 * @method static Effectra\Security\Token issuer($issuer):self
 * @method static Effectra\Security\Token set(mixed $data): string
 * @method static Effectra\Security\Token get(string $token): stdClass
 * @method static Effectra\Security\Token validateTime(stdClass $tokenDecoded) : bool
 * @method static Effectra\Security\Token generateToken(int $length): string
 * @method static Effectra\Security\Token generateTokenInt(int $length): int
 */

class Token extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SecurityToken::class;
    }
}

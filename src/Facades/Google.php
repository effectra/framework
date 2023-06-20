<?php 

namespace Effectra\Core\Facades;

use Effectra\Core\Application;
use Effectra\Facade;
use Effectra\ThirdParty\Google as ThirdPartyGoogle;

/** 
 * @method static \Effectra\ThirdParty\Google getAuthURL(): string
 * @method static \Effectra\ThirdParty\Google getAccessToken(string $code): string
 * @method static \Effectra\ThirdParty\Google getUser(string $token): ?array
 */

class Google extends Facade
{
    protected static function getFacadeAccessor()
    {
        static::setContainer(Application::container());
        return ThirdPartyGoogle::class;
    }
}
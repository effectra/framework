<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Services;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Models\User;
use Effectra\Core\Authentication\Models\UserLoginCode;
use Effectra\SqlQuery\Condition;

/**
 * Class UserLoginCodeService
 *
 * Service class for generating and verifying user login codes.
 *
 * @package Effectra\Core\Authentication\Services
 */
class UserLoginCodeService
{
    /**
     * Generate a login code for the given user.
     *
     * @param User $user The user for whom the code is generated.
     *
     * @return UserLoginCode
     */
    public function generate(User $user): UserLoginCode
    {
        $userLoginCode = new UserLoginCode();

        $code = random_int(100000, 999999);

        $userLoginCode->setCode((string) $code);
        $userLoginCode->setExpiration(new \DateTime('+10 minutes'));
        $userLoginCode->setUser($user);

        $userLoginCode->save();

        return $userLoginCode;
    }

    /**
     * Verify a user's login code.
     *
     * @param UserInterface $user The user for whom the code is being verified.
     * @param string        $code The login code to be verified.
     *
     * @return bool
     */
    public function verify(UserInterface $user, string $code): bool
    {
        /**
         * @var false|UserLoginCode $userLoginCode
         */
        $userLoginCode = UserLoginCode::where(
            (new Condition())->where(['user' => $user->getId(), 'code' => $code, 'isActive' => true])
        )->first();

        if (!$userLoginCode) {
            return false;
        }

        if ($userLoginCode->getExpiration() <= time()) {
            return false;
        }

        return true;
    }

    /**
     * Deactivate all active login codes for a user.
     *
     * @param User $user The user for whom active codes should be deactivated.
     */
    public function deactivateAllActiveCodes(User $user): void
    {
        UserLoginCode::where(
            (new Condition())->where(['user' => $user->getId(), 'isActive' => 1])
        )->first()?->setIsActive(0)->update();
    }
}

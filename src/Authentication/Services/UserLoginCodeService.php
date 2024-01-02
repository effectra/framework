<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Services;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Models\User;
use Effectra\Core\Authentication\Models\UserLoginCode;
use Effectra\SqlQuery\Condition;

class UserLoginCodeService
{

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

    public function deactivateAllActiveCodes(User $user): void
    {
        UserLoginCode::where(
            (new Condition())->where(['user' => $user->getId(), 'isActive' => 1])
        )->first()?->setIsActive(0)->update();
    }
}

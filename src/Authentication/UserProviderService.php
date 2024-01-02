<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Contracts\UserProviderServiceInterface;
use Effectra\Core\Authentication\Models\User;
use Effectra\SqlQuery\Condition;
use Exception;

class  UserProviderService implements UserProviderServiceInterface
{

    public function __construct()
    {
        if (!class_exists(User::class)) {
            throw new Exception("user class not exists");
        }
    }

    public function validateUser($user)
    {
        if ($user instanceof UserInterface) {
            throw new Exception("user class not implemented with 'Effectra\Core\Authentication\UserInterface'");
        }
    }

    public function getById(int|string $userId): ?UserInterface
    {
        return User::find((int) $userId);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {

        $email = $credentials['email'];
        $user = User::where((new Condition())->where(['email' => $email]))->first() ;
        if (!$user instanceof UserInterface) {
            return null;
        }
        return $user;
    }

    public function createUser(RegisterUserData $data): UserInterface
    {
        try {
            $entries  = $data->toArray();
            $user = new User();
            if (method_exists($user, 'toRegistration')) {
                $entries = $user->toRegistration() + $entries;
            }
            $user->setEntries($entries);
            $user->save();

            return $user;
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), '23000')) {
                throw new \Exception("Error Processing User Registration, 
                some columns added to User model hasn't default value or definition, please add method 'toRegistration' to 'App\Models\User' class for define columns ", 1);
            }
            throw new \Exception($e->getMessage());
        }
    }

    public function verifyUser(UserInterface $user): ?UserInterface
    {
        $user->setVerified(true);
        $user->setEmailVerifiedAt(new \DateTime());
        if($user->update()){
            return $user;
        }
        return null;
    }

    public function updatePassword(UserInterface $user, string $password): UserInterface
    {
        // $this->validateUser($user);
        $user->setPassword($password);
        $user->update();

        return $user;
    }
}

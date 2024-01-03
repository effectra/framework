<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Contracts\UserProviderServiceInterface;
use Effectra\Core\Authentication\Models\User;
use Effectra\SqlQuery\Condition;
use Exception;

/**
 * Class UserProviderService
 *
 * User provider service for retrieving and managing user information.
 */
class UserProviderService implements UserProviderServiceInterface
{
    /**
     * UserProviderService constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        // Check if the User class exists
        if (!class_exists(User::class)) {
            throw new Exception("User class not exists");
        }
    }

    /**
     * Validate if the provided user is an instance of UserInterface.
     *
     * @param mixed $user The user instance to validate.
     *
     * @throws Exception
     */
    public function validateUser($user)
    {
        // Validate if the user instance implements UserInterface
        if ($user instanceof UserInterface) {
            throw new Exception("User class not implemented with 'Effectra\Core\Authentication\UserInterface'");
        }
    }

    /**
     * Get a user by its ID.
     *
     * @param int|string $userId The user ID.
     *
     * @return UserInterface|null
     */
    public function getById(int|string $userId): ?UserInterface
    {
        return User::find((int) $userId);
    }

    /**
     * Get a user by its credentials (e.g., email).
     *
     * @param array $credentials The user credentials.
     *
     * @return UserInterface|null
     */
    public function getByCredentials(array $credentials): ?UserInterface
    {
        $email = $credentials['email'];
        $user = User::where((new Condition())->where(['email' => $email]))->first();
        
        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }

    /**
     * Create a new user.
     *
     * @param RegisterUserData $data The user registration data.
     *
     * @return UserInterface
     * @throws Exception
     */
    public function createUser(RegisterUserData $data): UserInterface
    {
        try {
            $entries  = $data->toArray();
            $user = new User();

            // Merge entries with additional registration data, if available
            if (method_exists($user, 'toRegistration')) {
                $entries = $user->toRegistration() + $entries;
            }

            $user->setEntries($entries);
            $user->save();

            return $user;
        } catch (\Exception $e) {
            // Handle registration errors
            if (strpos($e->getMessage(), '23000')) {
                throw new \Exception("Error Processing User Registration. 
                Some columns added to User model haven't default values or definitions. 
                Please add the method 'toRegistration' to the 'App\Models\User' class to define columns.", 1);
            }
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Verify a user.
     *
     * @param UserInterface $user The user to verify.
     *
     * @return UserInterface|null
     */
    public function verifyUser(UserInterface $user): ?UserInterface
    {
        $user->setVerified(true);
        $user->setEmailVerifiedAt(new \DateTime());

        // Update the user verification status
        if ($user->update()) {
            return $user;
        }

        return null;
    }

    /**
     * Update a user's password.
     *
     * @param UserInterface $user     The user for whom the password is updated.
     * @param string        $password The new password.
     *
     * @return UserInterface
     */
    public function updatePassword(UserInterface $user, string $password): UserInterface
    {
        // Validate the user
        // $this->validateUser($user);

        $user->setPassword($password);
        $user->update();

        return $user;
    }
}

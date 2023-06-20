<?php

declare(strict_types=1);

namespace Effectra\Core\Auth;

use App\Models\User;
use Effectra\Core\Facades\Token;
use Effectra\Security\Hash;

/**
 * Class Authentication
 *
 * Provides authentication functionality.
 */
class Authentication implements AuthInterface
{
    /**
     * Attempt to validate a login token.
     *
     * @param string $token The login token.
     * @return bool Whether the token is valid or not.
     */
    public function attemptLogin($token)
    {
        $decode = Token::get($token);
        return Token::validateTime($decode);
    }

    /**
     * Check the user's credentials.
     *
     * @return void
     */
    public function checkCredentials()
    {
        // Implementation
    }

    /**
     * Get the user's credentials from a token.
     *
     * @param string $token The token containing the user's credentials.
     * @return mixed The user's credentials.
     */
    public function getCredentials($token)
    {
        $decode = Token::get($token);
        return $decode->data;
    }

    /**
     * Log out the user.
     *
     * @return void
     */
    public function logout()
    {
        // Implementation
    }

    /**
     * Register a new user.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @param string $email The email of the new user.
     * @return User|null The newly created user object, or null if registration fails.
     */
    public function register(string $username, string $password, string $email)
    {
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => Hash::setPassword($password)
        ];
        $user = User::getEmail($email);
        dd($user, $data);
        if ($user) {
            return null;
        }
        $user = User::data($data)->create();
        if ($user) {
            return $user;
        }
        return null;
    }

    /**
     * Log in a user.
     *
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return User|null The logged in user object, or null if login fails.
     */
    public function login(string $email, string $password)
    {
        $user = (object) User::getEmail($email);
        if (!empty($user)) {
            $verify = Hash::verifyPassword($password, $user->password);
            if ($verify) {
                return $user;
            }
        }

        return null;
    }
}

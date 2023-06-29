<?php

declare(strict_types=1);

namespace Effectra\Core\Auth;

/**
 * Interface AuthInterface
 *
 * Defines the contract for authentication classes.
 */
interface AuthInterface
{
    /**
     * Attempt to validate a login token.
     *
     * @param string $token The login token.
     * @return bool Whether the token is valid or not.
     */
    public function attemptLogin($token);

    /**
     * Check the user's credentials.
     *
     * @return bool
     */
    public function checkCredentials(string $email, string $password):bool;

    /**
     * Log out the user.
     *
     * @return void
     */
    public function logout();

    /**
     * Register a new user.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @param string $email The email of the new user.
     * @return mixed The newly created user object, or null if registration fails.
     */
    public function register(string $username, string $password, string $email);

    /**
     * Log in a user.
     *
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return mixed The logged in user object, or null if login fails.
     */
    public function login(string $email, string $password);
}

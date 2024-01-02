<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

use Effectra\Core\Authentication\RegisterUserData;

/**
 * Interface AuthInterface
 *
 * Defines the contract for authentication classes.
 */
interface AuthInterface
{
    public function setToken(string $token);
    public function getToken() :?string;

    public function user(): ?UserInterface;
    /**
     * Attempt to validate a login token.
     *
     * @param array $credentials The login credentials.
     * @return bool Whether the token is valid or not.
     */
    public function attemptLogin(array $credentials);

    /**
     * Check the user's credentials.
     *
     * @return bool
     */
    public function checkCredentials(UserInterface $user, array $credentials): bool;

    /**
     * Log out the user.
     *
     * @return void
     */
    public function logout();

    /**
     * Register a new user.
     *
     * @param RegisterUserData $data The data of the new user.
     * @return mixed The newly created user object, or null if registration fails.
     */
    public function register(RegisterUserData $data):UserInterface;

    /**
     * Log in a user.
     *
     * @param UserInterface $user The login user.
     */
    public function login(UserInterface $user);


    public function updatePassword(UserInterface $user,string $password);

    public function verifyUser(UserInterface $user);
    public function sendVerifyMail(UserInterface $user);
    public function attemptTwoFactorLogin(array $data): bool;
    public function sendForgotPasswordMail(string $email): bool;
}


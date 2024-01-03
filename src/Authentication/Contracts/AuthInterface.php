<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

use Effectra\Core\Authentication\RegisterUserData;

/**
 * Interface AuthInterface
 *
 * Defines the contract for authentication-related operations.
 *
 * @package Effectra\Core\Authentication\Contracts
 */
interface AuthInterface
{
    /**
     * Set the authentication token.
     *
     * @param string $token
     */
    public function setToken(string $token);

    /**
     * Get the authentication token.
     *
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * Get the currently authenticated user.
     *
     * @return UserInterface|null
     */
    public function user(): ?UserInterface;

    /**
     * Attempt to log in with the provided credentials.
     *
     * @param array $credentials
     */
    public function attemptLogin(array $credentials);

    /**
     * Check if the provided credentials are valid for the given user.
     *
     * @param UserInterface $user
     * @param array         $credentials
     *
     * @return bool
     */
    public function checkCredentials(UserInterface $user, array $credentials): bool;

    /**
     * Log out the currently authenticated user.
     */
    public function logout();

    /**
     * Register a new user based on the provided data.
     *
     * @param RegisterUserData $data
     *
     * @return UserInterface
     */
    public function register(RegisterUserData $data): UserInterface;

    /**
     * Log in the provided user.
     *
     * @param UserInterface $user
     */
    public function login(UserInterface $user);

    /**
     * Update the password for the provided user.
     *
     * @param UserInterface $user
     * @param string        $password
     */
    public function updatePassword(UserInterface $user, string $password);

    /**
     * Verify the provided user.
     *
     * @param UserInterface $user
     */
    public function verifyUser(UserInterface $user);

    /**
     * Send a verification email to the provided user.
     *
     * @param UserInterface $user
     */
    public function sendVerifyMail(UserInterface $user);

    /**
     * Attempt two-factor login with the provided data.
     *
     * @param array $data
     *
     * @return bool
     */
    public function attemptTwoFactorLogin(array $data): bool;

    /**
     * Send a forgot password email to the user with the provided email address.
     *
     * @param string $email
     *
     * @return bool
     */
    public function sendForgotPasswordMail(string $email): bool;
}

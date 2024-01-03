<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

use Effectra\Core\Authentication\RegisterUserData;

/**
 * Interface UserProviderServiceInterface
 *
 * Defines the contract for user-related operations provided by a user provider service.
 *
 * @package Effectra\Core\Authentication\Contracts
 */
interface UserProviderServiceInterface
{
    /**
     * Get a user by their ID.
     *
     * @param int|string $userId
     *
     * @return UserInterface|null
     */
    public function getById(int|string $userId): ?UserInterface;

    /**
     * Get a user by their credentials.
     *
     * @param array $credentials
     *
     * @return UserInterface|null
     */
    public function getByCredentials(array $credentials): ?UserInterface;

    /**
     * Create a new user based on the provided registration data.
     *
     * @param RegisterUserData $data
     *
     * @return UserInterface
     */
    public function createUser(RegisterUserData $data): UserInterface;

    /**
     * Verify the provided user.
     *
     * @param UserInterface $user
     *
     * @return UserInterface|null
     */
    public function verifyUser(UserInterface $user): ?UserInterface;

    /**
     * Update the password for the provided user.
     *
     * @param UserInterface $user
     * @param string        $password
     *
     * @return UserInterface
     */
    public function updatePassword(UserInterface $user, string $password): UserInterface;
}

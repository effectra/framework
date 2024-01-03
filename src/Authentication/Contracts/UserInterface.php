<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

/**
 * Interface UserInterface
 *
 * Defines the contract for user-related operations.
 *
 * @package Effectra\Core\Authentication\Contracts
 */
interface UserInterface
{
    /**
     * Get the user's ID.
     *
     * @return int|string
     */
    public function getId(): int|string;

    /**
     * Get the username of the user.
     *
     * @return string
     */
    public function getUsername(): string;

    /**
     * Get the email address of the user.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Get the hashed password of the user.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Get the verification status of the user.
     *
     * @return int|null
     */
    public function getVerified(): ?int;

    /**
     * Set the password for the user.
     *
     * @param string $password
     *
     * @return static
     */
    public function setPassword(string $password): static;

    /**
     * Set the verification status for the user.
     *
     * @param bool $act
     */
    public function setVerified(bool $act);

    /**
     * Set the verified email timestamp for the user.
     *
     * @param \DateTime $verifiedAt
     */
    public function setEmailVerifiedAt(\DateTime $verifiedAt);

    /**
     * Update the user information.
     *
     * @return bool
     */
    public function update(): bool;
}

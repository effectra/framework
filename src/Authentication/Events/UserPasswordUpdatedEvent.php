<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Events;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\EventDispatcher\Event;

/**
 * Class UserPasswordUpdatedEvent
 *
 * Event triggered when a user's password is updated.
 */
class UserPasswordUpdatedEvent extends Event
{
    /**
     * UserPasswordUpdatedEvent constructor.
     *
     * @param UserInterface $user     The user whose password is updated.
     * @param string        $password The new password.
     */
    public function __construct(private readonly UserInterface $user, private readonly string $password)
    {
        // Constructor logic, if any
    }

    /**
     * Get the user whose password is updated.
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Get the new password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}

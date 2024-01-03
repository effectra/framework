<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Events;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\EventDispatcher\Event;

/**
 * Class UserLoggedEvent
 *
 * Event triggered when a user logs in.
 */
class UserLoggedEvent extends Event
{
    /**
     * UserLoggedEvent constructor.
     *
     * @param UserInterface $user The user who logged in.
     */
    public function __construct(private UserInterface $user)
    {
        // Constructor logic, if any
    }

    /**
     * Get the user who logged in.
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}

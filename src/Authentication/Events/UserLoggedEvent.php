<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Events;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\EventDispatcher\Event;

class UserLoggedEvent extends Event
{
    
    public function __construct(private UserInterface $user) {
       
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
    
}
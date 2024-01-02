<?php

declare(strict_types=1);

namespace Effectra\Core\Mail;

use Effectra\Mail\Mail as EffectraMail;

class Mail extends EffectraMail
{
    public function __construct()
    {
        $this->from = AppMail::fromAddress();
    }
}

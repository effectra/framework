<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Mail;

use Effectra\Core\Mail\Mail;

class ForgotPasswordMail extends Mail
{
    public function __construct(
        private string  $email,
        private string  $link,
    ) {
        parent::__construct();
        $this
            ->to($email)
            ->subject('Reset Password Link')
            ->html($this->templateHtml())
            ->text($this->templateText());
    }

    public function templateText()
    {
        return sprintf('click to this link to verify your account: %s', $this->link);
    }

    public function templateHtml()
    {
        return sprintf('<p>%s</p>', $this->templateText());
    }
}

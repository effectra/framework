<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Mail;

use Effectra\Core\Mail\Mail;

/**
 * Class ForgotPasswordMail
 *
 * Mail class for sending a forgot password email.
 *
 * @package Effectra\Core\Authentication\Mail
 */
class ForgotPasswordMail extends Mail
{
    /**
     * ForgotPasswordMail constructor.
     *
     * @param string $email The email address to send the mail to.
     * @param string $link  The password reset link.
     */
    public function __construct(
        private string $email,
        private string $link
    ) {
        parent::__construct();
        $this
            ->to($email)
            ->subject('Reset Password Link')
            ->html($this->templateHtml())
            ->text($this->templateText());
    }

    /**
     * Generate the text template for the email.
     *
     * @return string
     */
    public function templateText()
    {
        return sprintf('Click on this link to verify your account: %s', $this->link);
    }

    /**
     * Generate the HTML template for the email.
     *
     * @return string
     */
    public function templateHtml()
    {
        return sprintf('<p>%s</p>', $this->templateText());
    }
}

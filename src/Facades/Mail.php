<?php

namespace Effectra\Core\Facades;

use Effectra\Core\Facade;
use Effectra\Mail\Mailer;

/**
 * @method static \Effectra\Mail\Mailer send(callable $callback, $args): mixed

 * @method static \Effectra\Mail\Mailer getTo(): string

 * @method static \Effectra\Mail\Mailer getBcc(): string

 * @method static \Effectra\Mail\Mailer geTcc(): string

 * @method static \Effectra\Mail\Mailer getFrom(): string

 * @method static \Effectra\Mail\Mailer getSubject(): string

 * @method static \Effectra\Mail\Mailer getMsg(): array

 * @method static \Effectra\Mail\Mailer getText(): string

 * @method static \Effectra\Mail\Mailer getHtml(): string

 * @method static \Effectra\Mail\Mailer getReplyTo(): string

 * @method static \Effectra\Mail\Mailer to(string|array $emails): \Effectra\Mail\MailerInterface

 * @method static \Effectra\Mail\Mailer bcc(string|array $users): self

 * @method static \Effectra\Mail\Mailer tcc(string|array $users): self

 * @method static \Effectra\Mail\Mailer cc(string|array $users): self

 * @method static \Effectra\Mail\Mailer from(string|null $email = null): self

 * @method static \Effectra\Mail\Mailer subject(string $subject = ''): self

 * @method static \Effectra\Mail\Mailer msg(string $msg = ''): self

 * @method static \Effectra\Mail\Mailer text(string $text = ''): self

 * @method static \Effectra\Mail\Mailer html(string $html): self

 * @method static \Effectra\Mail\Mailer getAttachments(): array

 * @method static \Effectra\Mail\Mailer getAttachment(string $file): string

 * @method static \Effectra\Mail\Mailer attachment(string|array $files): self

 * @method static \Effectra\Mail\Mailer replayTo(string $email): self

 * @method static \Effectra\Mail\Mailer withHeaders(array|string $headers)

 * @method static \Effectra\Mail\Mailer withHtmlHeaders()

 * @method static \Effectra\Mail\Mailer getHeaders(): array|string

 * @method static \Effectra\Mail\Mailer sendMail(bool $html = false): bool

 * @method static \Effectra\Mail\Mailer HtmlTemplate(string|false $html = false): string

 * @method static \Effectra\Mail\Mailer attachmentHeaders($file_name, $text, $headers)

 */

class Mail extends Facade 
{
    protected static function getFacadeAccessor()
    {
        return Mailer::class;
    }
}

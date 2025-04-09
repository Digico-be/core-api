<?php

namespace App\libs\Brevo;

interface MailServiceInterface
{
    public function from(string $email, ?string $name = null): self;

    public function to(string $email): self;

    public function cc($emails): self;

    public function bcc($emails): self;

    public function subject(string $subject): self;

    public function view(string $view, array $data = []): self;

    public function content(string $content): self;

    public function attachments(array $attachments): self;

    public function send(): bool;
}

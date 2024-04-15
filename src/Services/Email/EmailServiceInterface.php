<?php

declare(strict_types=1);

namespace Masoretic\Services\Email;

interface EmailServiceInterface
{
    public function sendConfirmationEmail(
        string $to,
        string $name,
        string $subject,
        string $body
    ): int;
}

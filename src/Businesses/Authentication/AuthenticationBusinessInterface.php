<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Authentication;

use Psr\Http\Message\ServerRequestInterface;

interface AuthenticationBusinessInterface
{
    public function authenticate(
        ServerRequestInterface $request,
        string $email,
        string $password
    ): string;

    public function register(
        ServerRequestInterface $request,
        array $attributes
    ): array;

    public function checkEmailUniqueness(
        ServerRequestInterface $request,
        string $email
    ): void;
}

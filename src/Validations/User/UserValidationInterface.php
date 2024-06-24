<?php

declare(strict_types=1);

namespace Masoretic\Validations\User;

use Psr\Http\Message\ServerRequestInterface;

interface UserValidationInterface
{
    public function validateEmail(
        ServerRequestInterface $request,
        string $email
    ): void;

    public function validatePassword(
        ServerRequestInterface $request,
        string $password
    ): void;

    public function validateUserId(
        ServerRequestInterface $request,
        string $userId
    ): void;
}

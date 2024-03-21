<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Psr\Http\Message\ServerRequestInterface;

interface UserBusinessInterface
{
    public function getUser(
        ServerRequestInterface $request,
        string $userId
    ): array;

    public function listUsers(): array;

    public function updateUser(
        ServerRequestInterface $request,
        string $userId,
        array $attributes
    ): array;

    public function deleteUser(
        ServerRequestInterface $request,
        string $userId
    ): bool;
}
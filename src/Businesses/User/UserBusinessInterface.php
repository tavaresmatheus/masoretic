<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Models\User;
use Psr\Http\Message\ServerRequestInterface;

interface UserBusinessInterface
{
    public function registerUser(
        ServerRequestInterface $request,
        array $attributes
    ): array;

    public function getUser(
        ServerRequestInterface $request,
        string $userId
    ): array;
    // public function listUsers(): array;
    // public function updateUser(array $attributes): User;
    // public function deleteUser(string $userId): bool;
}
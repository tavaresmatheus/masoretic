<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Models\User;

interface UserBusinessInterface
{
    public function registerUser(array $attributes): array;
    // public function getUser(string $userId): User;
    // public function listUsers(): array;
    // public function updateUser(array $attributes): User;
    // public function deleteUser(string $userId): bool;
}
<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Psr\Http\Message\ServerRequestInterface;

interface UserBusinessInterface
{
    /**
     * @param int $userId
     * @return array<string, mixed>
     */
    public function getUser(
        ServerRequestInterface $request,
        int $userId
    ): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listUsers(): array;

    /**
     * @param int $userId
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function updateUser(
        ServerRequestInterface $request,
        int $userId,
        array $attributes
    ): array;

    public function deleteUser(
        ServerRequestInterface $request,
        int $userId
    ): bool;
}

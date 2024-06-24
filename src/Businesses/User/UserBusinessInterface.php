<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Psr\Http\Message\ServerRequestInterface;

interface UserBusinessInterface
{
    /**
     * @param string $userId
     * @return array<string, mixed>
     */
    public function getUser(
        ServerRequestInterface $request,
        string $userId
    ): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listUsers(): array;

    /**
     * @param string $userId
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
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

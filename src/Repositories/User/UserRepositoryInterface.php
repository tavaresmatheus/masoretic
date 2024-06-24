<?php

declare(strict_types=1);

namespace Masoretic\Repositories\User;

use Masoretic\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array<string, string> $user
     * @return int
     */
    public function create(array $user): int;

    /**
     * @param string $userId
     * @return array<string, mixed>
     */
    public function load(string $userId): array;

    /**
     * @param string $email
     * @return array<string, mixed>
     */
    public function loadByEmail(string $email): array;

    /**
     * @param string $activationHash
     * @return array<string, mixed>
     */
    public function loadByActivationHash(string $activationHash): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array;

    /**
     * @param array<string, string> $user
     * @return array<string, mixed>
     */
    public function update(array $user): array;

    /**
     * @param string $userId
     * @return int
     */
    public function delete(string $userId): int;
}

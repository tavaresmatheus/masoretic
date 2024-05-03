<?php

declare(strict_types=1);

namespace Masoretic\Repositories\User;

use Masoretic\Models\User;

interface UserRepositoryInterface
{
    public function create(array $user): int;
    public function load(string $id): array;
    public function loadByEmail(string $email): array;
    public function loadByActivationHash(string $activationHash): array;
    public function list(): array;
    public function update(array $user): array;
    public function delete(string $userId): int;
}

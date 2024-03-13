<?php

declare(strict_types=1);

namespace Masoretic\Repositories\User;

use Masoretic\Models\User;

interface UserRepositoryInterface
{
    public function create(User $user): int;
    public function load(string $id): array;
    public function loadByEmail(string $email): array;
    public function list(): array;
    public function update(User $user): array;
    public function delete(User $user): int;
}

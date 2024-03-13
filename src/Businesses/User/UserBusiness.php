<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Models\User;
use Masoretic\Repositories\User\UserRepositoryInterface;

class UserBusiness implements UserBusinessInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $attributes): array
    {
        $emailExists = $this->userRepository->loadByEmail($attributes['email']);
        if ($emailExists !== false) {
            throw new \Exception("Email already in use", 409);
        }

        $user = new User(
            null,
            $attributes['name'],
            $attributes['email'],
            $attributes['password'],
            null,
            null,
            null
        );

        $this->userRepository->create($user);

        return $this->userRepository->loadByEmail($user->getEmail());
    }
}
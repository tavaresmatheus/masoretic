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
        if ($emailExists !== []) {
            throw new \Exception('Email already in use', 409);
        }

        if (
            preg_match(
                '/^[a-z0-9.!#$&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*$/',
                $attributes['email']
            ) !== 1
        ) {
            throw new \Exception('Invalid email', 422);
        }

        if (
            preg_match(
                '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[a-zA-Z])(?=.*\W).{11,30}$/',
                $attributes['password']
            ) !== 1
        ) {
            throw new \Exception(
                'Invalid password, need a lower letter, upper letter, special char and length min 11 to 30 max',
                422
            );
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
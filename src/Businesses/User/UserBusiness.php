<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Masoretic\Validations\User\UserValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserBusiness implements UserBusinessInterface
{
    protected UserRepositoryInterface $userRepository;
    protected UserValidationInterface $userValidation;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserValidationInterface $userValidation
    )
    {
        $this->userRepository = $userRepository;
        $this->userValidation = $userValidation;
    }

    public function getUser(
        ServerRequestInterface $request,
        string $userId
    ): array
    {
        $this->userValidation->validateUserId($request, $userId);

        $user = $this->userRepository->load($userId);

        if ($user === []) {
            throw new DomainRuleException($request, 404, 'User don\'t exists.');
        }

        unset($user['password']);

        return $user;
    }

    public function listUsers(): array
    {
        $users = $this->userRepository->list();

        foreach ($users as $user) {
            unset($user['password']);
        }

        return $users;
    }

    public function updateUser(
        ServerRequestInterface $request,
        string $userId,
        array $attributes
    ): array
    {
        $this->userValidation->validateUserId($request, $userId);

        $user = $this->userRepository->load($userId);
        if ($user === []) {
            throw new DomainRuleException($request, 404, 'User don\'t exists.');
        }

        foreach ($attributes as $key => $value) {
            if ($key === 'email') {
                $this->checkEmailUniqueness($request, $value);
                $this->userValidation->validateEmail($request, $value);
            }

            if ($key === 'password') {
                $this->userValidation->validatePassword(
                    $request,
                    $value
                );
            }

            $user[$key] = $value;
        }

        $updatedUser = [
            'userId' => $user['user_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => password_hash($user['password'], PASSWORD_DEFAULT),
            'active' => $user['active']
        ];

        $user = $this->userRepository->update($updatedUser);

        unset($user['password']);

        return $user;
    }

    public function deleteUser(
        ServerRequestInterface $request,
        string $userId
    ): bool
    {
        $this->userValidation->validateUserId($request, $userId);

        $user = $this->userRepository->load($userId);
        if ($user === []) {
            throw new DomainRuleException($request, 404, 'User don\'t exists.');
        }

        $deletion = $this->userRepository->delete($userId);

        if ($deletion <= 0) {
            return false;
        }

        return true;
    }

    public function checkEmailUniqueness(
        ServerRequestInterface $request,
        string $email
    ): void
    {
        $emailExists = $this->userRepository->loadByEmail($email);
        if ($emailExists !== []) {
            throw new DomainRuleException(
                $request,
                409,
                'Email already in use.'
            );
        }
    }
}
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

    public function registerUser(
        ServerRequestInterface $request,
        array $attributes
    ): array
    {
        $this->checkEmailUniqueness($request, $attributes['email']);
        $this->userValidation->validateEmail($request, $attributes['email']);
        $this->userValidation->validatePassword(
            $request,
            $attributes['password']
        );

        $user = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => password_hash(
                $attributes['password'],
                PASSWORD_DEFAULT
            ),
        ];

        $this->userRepository->create($user);

        return $this->userRepository->loadByEmail($user['email']);
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

        return $user;
    }

    public function listUsers(): array
    {
        $users = $this->userRepository->list();

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

        $this->checkEmailUniqueness($request, $attributes['email']);
        $this->userValidation->validateEmail($request, $attributes['email']);
        $this->userValidation->validatePassword(
            $request,
            $attributes['password']
        );

        $updatedUser = [
            'userId' => $user['user_id'],
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
        ];

        return $this->userRepository->update($updatedUser);
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
<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Models\User;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserBusiness implements UserBusinessInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(
        ServerRequestInterface $request,
        array $attributes
    ): array
    {
        $this->validateEmailUniqueness($request, $attributes['email']);
        $this->validateEmail($request, $attributes['email']);
        $this->validatePassword($request, $attributes['password']);

        $user = new User(
            null,
            $attributes['name'],
            $attributes['email'],
            password_hash($attributes['password'], PASSWORD_DEFAULT),
            null,
            null,
            null
        );

        $this->userRepository->create($user);

        return $this->userRepository->loadByEmail($user->getEmail());
    }

    public function getUser(
        ServerRequestInterface $request,
        string $userId
    ): array
    {
        $this->validateUserId($request, $userId);

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
        $this->validateUserId($request, $userId);

        $user = $this->userRepository->load($userId);
        if ($user === []) {
            throw new DomainRuleException($request, 404, 'User don\'t exists.');
        }

        $this->validateEmailUniqueness($request, $attributes['email']);
        $this->validateEmail($request, $attributes['email']);
        $this->validatePassword($request, $attributes['password']);

        $updatedUser = new User(
            $user['user_id'],
            $attributes['name'],
            $attributes['email'],
            $attributes['password'],
            null,
            null,
            null
        );
        return $this->userRepository->update($updatedUser);
    }

    public function deleteUser(
        ServerRequestInterface $request,
        string $userId
    ): bool
    {
        $this->validateUserId($request, $userId);

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

    public function validateEmailUniqueness(
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

    public function validateEmail(
        ServerRequestInterface $request,
        string $email
    ): void
    {
        if (
            preg_match(
                '/^[a-z0-9.!#$&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}' .
                '[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*$/',
                $email
            ) !== 1
        ) {
            throw new DomainRuleException(
                $request,
                422,
                'Invalid email.'
            );
        }
    }

    public function validatePassword(
        ServerRequestInterface $request,
        string $password
    ): void
    {
        if (
            preg_match(
                '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[a-zA-Z])' .
                '(?=.*\W).{11,30}$/',
                $password
            ) !== 1
        ) {
            throw new DomainRuleException(
                $request,
                422,
                'Invalid password, need a lower letter, upper letter, ' .
                'special char and length min 11 to 30 max.'
            );
        }
    }

    public function validateUserId(
        ServerRequestInterface $request,
        string $userId
    ): void
    {
        if (
            preg_match(
                '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]' .
                '{4}-[0-9A-Fa-f]{12}$/',
                $userId
            ) !== 1
        ) {
            throw new DomainRuleException($request, 422, 'Invalid user id.');
        }
    }
}
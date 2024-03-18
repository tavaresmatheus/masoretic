<?php

declare(strict_types=1);

namespace Masoretic\Businesses\User;

use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Models\User;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

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
        $emailExists = $this->userRepository->loadByEmail($attributes['email']);
        if ($emailExists !== []) {
            throw new DomainRuleException(
                $request,
                409,
                'Email already in use.'
            );
        }

        if (
            preg_match(
                '/^[a-z0-9.!#$&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}' .
                '[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*$/',
                $attributes['email']
            ) !== 1
        ) {
            throw new DomainRuleException(
                $request,
                422,
                'Invalid email.'
            );
        }

        if (
            preg_match(
                '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[a-zA-Z])' .
                '(?=.*\W).{11,30}$/',
                $attributes['password']
            ) !== 1
        ) {
            throw new DomainRuleException(
                $request,
                422,
                'Invalid password, need a lower letter, upper letter, ' .
                'special char and length min 11 to 30 max'
            );
        }

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
        if (
            preg_match(
                '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]' .
                '{4}-[0-9A-Fa-f]{12}$/',
                $userId
            ) !== 1
        ) {
            throw new DomainRuleException($request, 422, 'Invalid user id.');
        }

        $user = $this->userRepository->load($userId);

        if ($user === []) {
            throw new DomainRuleException($request, 404, 'User don\'t exists');
        }

        return $user;
    }
}
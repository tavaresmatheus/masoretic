<?php

declare(strict_types=1);

namespace Masoretic\Validations\User;

use Masoretic\Exceptions\DomainRuleException;
use Psr\Http\Message\ServerRequestInterface;

class UserValidation implements UserValidationInterface
{
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
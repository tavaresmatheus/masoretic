<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Authentication;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Masoretic\Validations\User\UserValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationBusiness implements AuthenticationBusinessInterface
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

    public function authenticate(
        ServerRequestInterface $request,
        string $email,
        string $password
    ): string
    {
        $user = $this->userRepository->loadByEmail($email);
        if ($user === []) {
            throw new DomainRuleException(
                $request,
                403,
                'Invalid credentials.'
            );
        }

        if (! password_verify($password, $user['password'])) {
            throw new DomainRuleException(
                $request,
                403,
                'Invalid credentials.'
            );
        }

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->safeLoad();
        $secretJwtKey = $_ENV['SECRET_JWT_KEY'];
        $jwtAlgorithm = $_ENV['JWT_ALGORITHM'];

        $payload = [
            'iis' => 'http://example.org',
            'aud' => 'http://example.com',
            'sub' => $user['user_id'],
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 3600 //1h
        ];

        return JWT::encode($payload, $secretJwtKey, $jwtAlgorithm);
    }

    public function register(
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

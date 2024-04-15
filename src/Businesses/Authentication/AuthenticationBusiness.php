<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Authentication;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Masoretic\Services\Email\EmailServiceInterface;
use Masoretic\Validations\User\UserValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationBusiness implements AuthenticationBusinessInterface
{
    protected UserRepositoryInterface $userRepository;
    protected UserValidationInterface $userValidation;
    protected EmailServiceInterface $emailService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserValidationInterface $userValidation,
        EmailServiceInterface $emailService
    )
    {
        $this->userRepository = $userRepository;
        $this->userValidation = $userValidation;
        $this->emailService = $emailService;
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
            'iis' => $_ENV['APP_URL'],
            'aud' => $_ENV['APP_URL'],
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
            'activationHash' => md5(bin2hex(random_bytes(16)))
        ];

        $this->userRepository->create($user);

        $this->emailService->sendConfirmationEmail(
            'tavares.matheus.sp@gmail.com',
            $user['name'],
            'Email confirmation - Masoretic Library Platform',
            '<link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link
                href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
                rel="stylesheet"
            >
            <style>
                body {
                    font-family: \'Roboto\', sans-serif;
                }
            </style>
            <h1>We are happy to have you with us!</h1>
            <div>
                <p>Confirm your e-mail giving the code below in our platform:</p>
                <div><span><b>' . $user['activationHash'] . '</b></span></div>
                <p>It\'s ok, you don\'t need to remeber it, just paste it on your register form.</p>
            </div>'
        );

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

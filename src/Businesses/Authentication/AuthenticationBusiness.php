<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Authentication;

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

        $secretJwtKey = getenv('SECRET_JWT_KEY');
        $jwtAlgorithm = getenv('JWT_ALGORITHM');

        $payload = [
            'iis' => getenv('APP_URL'),
            'aud' => getenv('APP_URL'),
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

        if (getenv('APP_ENV') !== 'local') {
            $emailTemplatePath = __DIR__ .
                '/../../../templates/email-confirmation-template.html';
            $emailTemplate = fopen($emailTemplatePath, 'r');
            $emailTemplate = fread($emailTemplate, filesize($emailTemplatePath));
            $email = str_replace('{{userName}}', $user['name'], $emailTemplate);
            $email = str_replace(
                '{{activationHash}}',
                $user['activationHash'],
                $email
            );
            $email = str_replace(
                '{{linkToConfirm}}',
                getenv('APP_URL') . '/api/confirm/' . $user['activationHash'],
                $email
            );
    
            $this->emailService->sendConfirmationEmail(
                $user['email'],
                $user['name'],
                'Email confirmation - Masoretic Library Platform',
                $email
            );
        }

        return $this->userRepository->loadByEmail($user['email']);
    }

    public function confirmEmail(
        ServerRequestInterface $request,
        string $activationHash
    ): bool
    {
        $user = $this->userRepository->loadByActivationHash($activationHash);

        if ($user === []) {
            throw new DomainRuleException(
                $request,
                422,
                'Invalid activation hash.'
            );
        }

        $user['active'] = 1;

        $userConfirmatedEmail = $this->userRepository->update($user);

        if ($userConfirmatedEmail === []) {
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

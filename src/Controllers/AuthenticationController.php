<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\Businesses\Authentication\AuthenticationBusinessInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationController
{
    protected AuthenticationBusinessInterface $authenticationBusiness;

    public function __construct(
        AuthenticationBusinessInterface $authenticationBusiness
    ) {
        $this->authenticationBusiness = $authenticationBusiness;
    }

    public function authenticate(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $credentials = (array) $request->getParsedBody();
        $jwt = $this->authenticationBusiness->authenticate(
            $request,
            $credentials['email'],
            $credentials['password']
        );

        $authenticated = json_encode(['authorized' => true, 'token' => $jwt], JSON_THROW_ON_ERROR);
        $response->getBody()->write($authenticated);

        return $response;
    }

    public function register(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $userCreated = json_encode(
            $this->authenticationBusiness->register(
                $request,
                (array) $request->getParsedBody()
            ),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($userCreated);
        return $response;
    }

    /**
     * @param array<string, string> $urlParam
     */
    public function emailConfirmation(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $emailConfirmated = $this->authenticationBusiness->confirmEmail(
            $request,
            $urlParam['activationHash']
        );

        $emailConfirmated = json_encode(
            ['emailConfirmated' => $emailConfirmated],
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($emailConfirmated);
        return $response;
    }
}

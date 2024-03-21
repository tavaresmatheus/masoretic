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
    )
    {
        $this->authenticationBusiness = $authenticationBusiness;
    }

    public function authenticate(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $credentials = $request->getParsedBody();
        $jwt = $this->authenticationBusiness->authenticate(
            $request,
            $credentials['email'],
            $credentials['password']
        );

        $authenticated = json_encode(['Authorized' => true, 'Token' => $jwt]);
        $response->getBody()->write($authenticated);

        return $response;
    }

    public function register(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $userCreated = json_encode(
            $this->authenticationBusiness->register(
                $request,
                $request->getParsedBody()
            )
        );

        $response->getBody()->write($userCreated);
        return $response;
    }
}

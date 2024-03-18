<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\Businesses\User\UserBusinessInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    protected UserBusinessInterface $userBusiness;

    public function __construct(UserBusinessInterface $userBusiness)
    {
        $this->userBusiness = $userBusiness;
    }

    public function registerUser(Request $request, Response $response): Response
    {
        $userCreated = json_encode(
            $this->userBusiness->registerUser(
                $request,
                $request->getParsedBody()
            )
        );

        $response->getBody()->write($userCreated);
        return $response;
    }

    public function showUser(Request $request, Response $response, array $urlParam): Response
    {
        $userShowed = json_encode(
            $this->userBusiness->getUser(
                $request,
                $urlParam['id']
            )
        );

        $response->getBody()->write($userShowed);
        return $response;
    }
}

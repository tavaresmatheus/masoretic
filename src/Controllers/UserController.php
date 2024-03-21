<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\Businesses\User\UserBusinessInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    protected UserBusinessInterface $userBusiness;

    public function __construct(UserBusinessInterface $userBusiness)
    {
        $this->userBusiness = $userBusiness;
    }

    public function showUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface
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

    public function listUsers(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $usersListed = ['users' => $this->userBusiness->listUsers()];
        $usersListed = json_encode($usersListed);
        $response->getBody()->write($usersListed);
        return $response;
    }

    public function updateUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface
    {
        $userUpdated = json_encode(
            $this->userBusiness->updateUser(
                $request,
                $urlParam['id'],
                $request->getParsedBody()
            )
        );

        $response->getBody()->write($userUpdated);
        return $response;
    }

    public function deleteUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface
    {
        $userDeleted = [
            'user' => $urlParam['id'],
            'deleted' => $this->userBusiness->deleteUser(
                $request,
                $urlParam['id'],
                $request->getParsedBody()
            )
        ];

        $userDeleted = json_encode($userDeleted);

        $response->getBody()->write($userDeleted);
        return $response;
    }
}

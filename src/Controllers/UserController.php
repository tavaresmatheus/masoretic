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

    /**
     * @param array<string, string> $urlParam
     */
    public function showUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $userShowed = json_encode(
            $this->userBusiness->getUser(
                $request,
                (int) $urlParam['id']
            ),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($userShowed);
        return $response;
    }

    public function listUsers(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $usersListed = ['users' => $this->userBusiness->listUsers()];
        $usersListed = json_encode($usersListed, JSON_THROW_ON_ERROR);
        $response->getBody()->write($usersListed);
        return $response;
    }

    /**
     * @param array<string, string> $urlParam
     */
    public function updateUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $userUpdated = json_encode(
            $this->userBusiness->updateUser(
                $request,
                (int) $urlParam['id'],
                (array) $request->getParsedBody()
            ),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($userUpdated);
        return $response;
    }

    /**
     * @param array<string, string> $urlParam
     */
    public function deleteUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $userDeleted = [
            'user' => $urlParam['id'],
            'deleted' => $this->userBusiness->deleteUser(
                $request,
                (int) $urlParam['id']
            )
        ];

        $userDeleted = json_encode($userDeleted, JSON_THROW_ON_ERROR);

        $response->getBody()->write($userDeleted);
        return $response;
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\Middlewares;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Masoretic\Exceptions\AuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthenticationMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $secretJwtKey = $_ENV['SECRET_JWT_KEY'];
        $jwtAlgorithm = $_ENV['JWT_ALGORITHM'];

        $jwt = $request->getHeader('Authorization')[0];
        $jwt = substr($jwt, 7, strlen($jwt));

        try {
            if (empty($jwt)) {
                throw new AuthenticationException(
                    $request,
                    403,
                    'Token is mandatory.'
                );
            }

            JWT::decode($jwt, new Key($secretJwtKey, $jwtAlgorithm));

            $response = $handler->handle($request);

            return $response;
        } catch (\Throwable $th) {
            throw new AuthenticationException(
                $request,
                403,
                'Invalid/Expired token.'
            );
        }
    }
}

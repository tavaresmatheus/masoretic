<?php

declare(strict_types=1);

namespace Masoretic\Middlewares;

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
    ): ResponseInterface {
        $secretJwtKey = is_string(getenv('SECRET_JWT_KEY')) ? getenv('SECRET_JWT_KEY') : '';
        $jwtAlgorithm = is_string(getenv('JWT_ALGORITHM')) ? getenv('JWT_ALGORITHM') : '';

        if (empty($request->getHeader('Authorization'))) {
            throw new AuthenticationException(
                $request,
                403,
                'Authorization header not found.'
            );
        }

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
        } catch (\Throwable $th) {
            throw new AuthenticationException(
                $request,
                403,
                'Invalid/Expired token.'
            );
        }

        $response = $handler->handle($request);

        return $response;
    }
}

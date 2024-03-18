<?php

declare(strict_types=1);

namespace Masoretic\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ContentTypeJsonMiddleware
{
    public function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response
    {
        $response = $handler->handle($request);
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}

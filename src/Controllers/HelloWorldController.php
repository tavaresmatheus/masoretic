<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\DBAL\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelloWorldController
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function helloWorld(Request $request, Response $response): Response
    {
        $response->getBody()->write('Hello world!');
        return $response;
    }
}

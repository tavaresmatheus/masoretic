<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\Businesses\Book\BookBusinessInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BookController
{
    protected BookBusinessInterface $bookBusiness;

    public function __construct(BookBusinessInterface $bookBusiness)
    {
        $this->bookBusiness = $bookBusiness;
    }

    public function createBook(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $bookCreated = json_encode(
            $this->bookBusiness->createBook($request, (array) $request->getParsedBody()),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($bookCreated);
        return $response;
    }
}

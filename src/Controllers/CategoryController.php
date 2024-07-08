<?php

declare(strict_types=1);

namespace Masoretic\Controllers;

use Masoretic\Businesses\Category\CategoryBusinessInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    protected CategoryBusinessInterface $categoryBusiness;

    public function __construct(CategoryBusinessInterface $categoryBusiness)
    {
        $this->categoryBusiness = $categoryBusiness;
    }

    public function createCategory(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $categoryCreated = json_encode(
            $this->categoryBusiness->createCategory($request, (array) $request->getParsedBody()),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($categoryCreated);
        return $response;
    }

    public function listCategories(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $categoriesListed = json_encode(
            ['categories' => $this->categoryBusiness->listCategories()],
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($categoriesListed);
        return $response;
    }
}

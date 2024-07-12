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

    /**
     * @param array<string, string> $urlParam
     */
    public function showCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $categoryShowed = json_encode(
            $this->categoryBusiness->getCategory(
                $request,
                (int) $urlParam['id']
            ),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($categoryShowed);
        return $response;
    }

    /**
     * @param array<string, string> $urlParam
     */
    public function updateCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $categoryUpdated = json_encode(
            $this->categoryBusiness->updateCategory(
                $request,
                (int) $urlParam['id'],
                (array) $request->getParsedBody()
            ),
            JSON_THROW_ON_ERROR
        );

        $response->getBody()->write($categoryUpdated);
        return $response;
    }

    /**
     * @param array<string, string> $urlParam
     */
    public function deleteCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $urlParam
    ): ResponseInterface {
        $categoryDeleted = [
            'category' => $urlParam['id'],
            'deleted' => $this->categoryBusiness->deleteCategory(
                $request,
                (int) $urlParam['id']
            )
        ];

        $categoryDeleted = json_encode($categoryDeleted, JSON_THROW_ON_ERROR);

        $response->getBody()->write($categoryDeleted);
        return $response;
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Category;

use DomainException;
use Masoretic\Repositories\Category\CategoryRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryBusiness implements CategoryBusinessInterface
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function createCategory(ServerRequestInterface $request, array $attributes): array
    {
        //validate attributes;
        $categoryCreated = $this->categoryRepository->create($attributes);

        if ($categoryCreated <= 0) {
            throw new DomainException('We were unable to create the specified category');
        }

        return $this->categoryRepository->loadCategoryByName($attributes['name']);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listCategories(): array
    {
        $categories = $this->categoryRepository->list();

        return $categories;
    }
}

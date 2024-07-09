<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Category;

use DomainException;
use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Repositories\Category\CategoryRepositoryInterface;
use Masoretic\Validations\Category\CategoryValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryBusiness implements CategoryBusinessInterface
{
    protected CategoryRepositoryInterface $categoryRepository;
    protected CategoryValidationInterface $categoryValidation;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryValidationInterface $categoryValidation
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryValidation = $categoryValidation;
    }

    /**
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function createCategory(ServerRequestInterface $request, array $attributes): array
    {
        $this->checkCategoryUniqueness($request, $attributes['name']);

        $categoryCreated = $this->categoryRepository->create($attributes);

        if ($categoryCreated <= 0) {
            throw new DomainException('We were unable to create the specified category.');
        }

        return $this->categoryRepository->loadCategoryByName($attributes['name']);
    }

    /**
     * @param int $categoryId
     * @return array<string, mixed>
     */
    public function getCategory(
        ServerRequestInterface $request,
        int $categoryId
    ): array {
        $this->categoryValidation->validateCategoryId($request, $categoryId);

        $category = $this->categoryRepository->load($categoryId);

        if ($category === []) {
            throw new DomainRuleException($request, 404, 'Category don\'t exists.');
        }

        return $category;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listCategories(): array
    {
        $categories = $this->categoryRepository->list();

        return $categories;
    }

    /**
     * @param int $categoryId
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function updateCategory(
        ServerRequestInterface $request,
        int $categoryId,
        array $attributes
    ): array {
        $this->categoryValidation->validatecategoryId($request, $categoryId);

        $category = $this->categoryRepository->load($categoryId);
        if ($category === []) {
            throw new DomainRuleException($request, 404, 'Category don\'t exists.');
        }

        $this->checkCategoryUniqueness($request, $attributes['name']);

        $category['name'] = $attributes['name'];

        $category = $this->categoryRepository->update($category);

        return $category;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $categoryName
     * @return void
     */
    private function checkCategoryUniqueness(ServerRequestInterface $request, string $categoryName): void
    {
        $categoryExists = $this->categoryRepository->loadCategoryByName($categoryName);
        if ($categoryExists !== []) {
            throw new DomainRuleException(
                $request,
                409,
                'Category already exists.'
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\Repositories\Category;

interface CategoryRepositoryInterface
{
    /**
     * @param array<string, string> $category
     * @return int
     */
    public function create(array $category): int;

    /**
     * @param int $categoryId
     * @return array<string, mixed>
     */
    public function load(int $categoryId): array;

    /**
     * @param string $categoryName
     * @return array<string, mixed>
     */
    public function loadCategoryByName(string $categoryName): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array;
}

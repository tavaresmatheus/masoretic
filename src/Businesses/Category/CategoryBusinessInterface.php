<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Category;

use Psr\Http\Message\ServerRequestInterface;

interface CategoryBusinessInterface
{
    /**
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function createCategory(
        ServerRequestInterface $request,
        array $attributes
    ): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listCategories(): array;

    /**
     * @param int $categoryId
     * @return array<string, mixed>
     */
    public function getCategory(ServerRequestInterface $request, int $categoryId): array;

    /**
     * @param int $categoryId
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function updateCategory(ServerRequestInterface $request, int $categoryId, array $attributes): array;
}

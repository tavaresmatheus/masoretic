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
}

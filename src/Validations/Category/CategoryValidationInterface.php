<?php

declare(strict_types=1);

namespace Masoretic\Validations\Category;

use Psr\Http\Message\ServerRequestInterface;

interface CategoryValidationInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param int $categoryId
     * @return void
     */
    public function validateCategoryId(
        ServerRequestInterface $request,
        int $categoryId
    ): void;
}

<?php

declare(strict_types=1);

namespace Masoretic\Validations\Category;

use Masoretic\Exceptions\DomainRuleException;
use Psr\Http\Message\ServerRequestInterface;

class CategoryValidation implements CategoryValidationInterface
{
    public function validateCategoryId(
        ServerRequestInterface $request,
        int $categoryId
    ): void {
        if ($categoryId <= 0) {
            throw new DomainRuleException($request, 422, 'Invalid category id.');
        }
    }
}

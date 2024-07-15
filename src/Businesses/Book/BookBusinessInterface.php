<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Book;

use Psr\Http\Message\ServerRequestInterface;

interface BookBusinessInterface
{
    /**
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function createBook(
        ServerRequestInterface $request,
        array $attributes
    ): array;
}

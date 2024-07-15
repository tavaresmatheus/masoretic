<?php

declare(strict_types=1);

namespace Masoretic\Repositories\Book;

interface BookRepositoryInterface
{
    /**
     * @param array<string, mixed> $book
     * @return int
     */
    public function create(array $book): int;

    /**
     * @param string $bookIsbn
     * @return array<string, mixed>
     */
    public function loadBookByIsbn(string $bookIsbn): array;
}

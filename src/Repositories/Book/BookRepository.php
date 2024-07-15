<?php

declare(strict_types=1);

namespace Masoretic\Repositories\Book;

use Masoretic\DBAL\Database;

class BookRepository implements BookRepositoryInterface
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param array<string, mixed> $book
     * @return int
     */
    public function create(array $book): int
    {
        try {
            return (int) $this->database->getQueryBuilder()
                ->insert('books')
                ->values(
                    [
                        'title' => ':title',
                        'authors' => ':authors',
                        'isbn_13' => ':isbn',
                        'publisher' => ':publisher',
                        'publication_date' => ':publicationDate',
                        'description' => ':description',
                        'language' => ':language',
                        'total_pages' => ':totalPages',
                        'stock' => ':stock',
                        'location' => ':location',
                        'keywords' => ':keywords',
                        'cover_image' => ':coverImage',
                        'edition' => ':edition',
                        'price' => ':price',
                        'acquisition_source' => ':acquisitionSource',
                        'deleted' => ':deleted',
                    ]
                )
                ->setParameter('title', $book['title'])
                ->setParameter('authors', $book['authors'])
                ->setParameter('isbn', $book['isbn'])
                ->setParameter('publisher', $book['publisher'])
                ->setParameter('publicationDate', $book['publicationDate'])
                ->setParameter('description', $book['description'])
                ->setParameter('language', $book['language'])
                ->setParameter('totalPages', $book['totalPages'])
                ->setParameter('stock', $book['stock'])
                ->setParameter('location', $book['location'])
                ->setParameter('keywords', $book['keywords'])
                ->setParameter('coverImage', $book['coverImage'])
                ->setParameter('edition', $book['edition'])
                ->setParameter('price', $book['price'])
                ->setParameter('acquisitionSource', $book['acquisitionSource'])
                ->setParameter('deleted', 0)
                ->executeStatement();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $bookIsbn
     * @return array<string, mixed>
    */
    public function loadBookByIsbn(string $bookIsbn): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('
                title,
                authors,
                isbn_13,
                publisher,
                publication_date,
                description,
                language,
                total_pages,
                stock,
                location,
                keywords,
                cover_image,
                edition,
                price,
                acquisition_source
            ')
            ->from('books')
            ->where('deleted = :deleted')
            ->andWhere('isbn_13 = :isbn')
            ->setParameter('deleted', 0)
            ->setParameter('isbn', $bookIsbn)
            ->fetchAssociative();

        if ($result === false) {
            return [];
        }

        return $result;
    }
}

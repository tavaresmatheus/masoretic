<?php

declare(strict_types=1);

namespace Masoretic\Businesses\Book;

use DateTimeImmutable;
use Masoretic\Exceptions\DomainRuleException;
use Masoretic\Repositories\Book\BookRepositoryInterface;
use Masoretic\Validations\Book\BookValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class BookBusiness implements BookBusinessInterface
{
    protected BookRepositoryInterface $bookRepository;
    protected BookValidationInterface $bookValidation;

    public function __construct(BookRepositoryInterface $bookRepository, BookValidationInterface $bookValidation)
    {
        $this->bookRepository = $bookRepository;
        $this->bookValidation = $bookValidation;
    }

    /**
     * @param array<string, string> $attributes
     * @return array<string, mixed>
     */
    public function createBook(ServerRequestInterface $request, array $attributes): array
    {
        $this->bookValidation->validateTitle($request, $attributes['title']);
        $this->bookValidation->validateAuthor($request, $attributes['authors']);

        if (! is_string($attributes['isbn'])) {
            $attributes['isbn'] = '';
        }
        $this->bookValidation->validateIsbn($request, $attributes['isbn']);
        $this->bookValidation->validatePublisher($request, $attributes['publisher']);

        $publicationDate = new DateTimeImmutable($attributes['publicationDate']);
        $this->bookValidation->validatePublicationDate($request, $publicationDate);
        $attributes['publicationDate'] = $publicationDate->format('Y-m-d');

        $this->bookValidation->validateDescription($request, $attributes['description']);
        $this->bookValidation->validateLanguage($request, $attributes['language']);

        $totalPages = filter_var($attributes['totalPages'], FILTER_VALIDATE_INT);
        if ($totalPages === false) {
            $totalPages = 0;
        }
        $this->bookValidation->validateTotalPages($request, $totalPages);
        $attributes['totalPages'] = $totalPages;

        $stock = filter_var($attributes['stock'], FILTER_VALIDATE_INT);
        if ($stock === false) {
            $stock = 0;
        }
        $this->bookValidation->validateStock($request, $stock);
        $attributes['stock'] = $stock;

        $location = is_string($attributes['location']) ? $attributes['location'] : null;
        $this->bookValidation->validateLocation($request, $location);
        $attributes['location'] = $location;

        $keywords = is_string($attributes['keywords']) ? $attributes['keywords'] : null;
        $this->bookValidation->validateKeyword($request, $keywords);
        $attributes['keywords'] = $keywords;

        $coverImage = is_string($attributes['coverImage']) ? $attributes['coverImage'] : null;
        $this->bookValidation->validateCoverImage($request, $coverImage);
        $attributes['coverImage'] = $coverImage;

        $edition = is_string($attributes['edition']) ? $attributes['edition'] : null;
        $this->bookValidation->validateEdition($request, $edition);
        $attributes['edition'] = $edition;

        $price = filter_var($attributes['price'], FILTER_VALIDATE_INT);
        if ($price === false) {
            $price = 0;
        }
        $price = $price * 100;
        $this->bookValidation->validatePrice($request, $price);
        $attributes['price'] = $price;

        $acquisitionSource = is_string($attributes['acquisitionSource']) ? $attributes['acquisitionSource'] : null;
        $this->bookValidation->validateAcquisitionSource($request, $acquisitionSource);
        $attributes['acquisitionSource'] = $acquisitionSource;

        $this->checkBookUniqueness($request, $attributes['isbn']);

        $bookCreated = $this->bookRepository->create($attributes);

        if ($bookCreated <= 0) {
            throw new DomainRuleException($request, 422, 'We were unable to create the specified book.');
        }

        return $this->bookRepository->loadBookByIsbn($attributes['isbn']);
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $bookIsbn
     * @return void
     */
    private function checkBookUniqueness(ServerRequestInterface $request, string $bookIsbn): void
    {
        $bookExists = $this->bookRepository->loadBookByIsbn($bookIsbn);
        if ($bookExists !== []) {
            throw new DomainRuleException(
                $request,
                409,
                'Book already exists.'
            );
        }
    }
}

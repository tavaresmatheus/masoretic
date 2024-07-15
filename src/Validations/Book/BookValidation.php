<?php

declare(strict_types=1);

namespace Masoretic\Validations\Book;

use DateTimeImmutable;
use Masoretic\Exceptions\DomainRuleException;
use Psr\Http\Message\ServerRequestInterface;

class BookValidation implements BookValidationInterface
{
    public function validateBookId(ServerRequestInterface $request, int $bookId): void
    {
        if ($bookId <= 0) {
            throw new DomainRuleException($request, 422, 'Invalid book id.');
        }
    }

    public function validateTitle(ServerRequestInterface $request, string $title): void
    {
        if (! is_string($title) || strlen($title) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid book title.');
        }
    }

    public function validateAuthor(ServerRequestInterface $request, string $author): void
    {
        if (! is_string($author) || strlen($author) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid book author.');
        }
    }

    public function validateIsbn(ServerRequestInterface $request, string $isbn): void
    {
        if (! is_string($isbn)) {
            throw new DomainRuleException($request, 422, 'Book isbn should be a string.');
        }

        if (preg_match('/\b\d{3}-\d{2}-\d{5}-\d{2}-\d\b/', $isbn) === false) {
            throw new DomainRuleException($request, 422, 'Invalid isbn-13.');
        }
    }

    public function validatePublisher(ServerRequestInterface $request, string $publisher): void
    {
        if (! is_string($publisher) || strlen($publisher) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid book publisher.');
        }
    }

    public function validatePublicationDate(ServerRequestInterface $request, DateTimeImmutable $publicationDate): void
    {
        if (! $publicationDate instanceof DateTimeImmutable) {
            throw new DomainRuleException($request, 422, 'Invalid book publication date.');
        }

        $dateTimeErrors = $publicationDate->getLastErrors();

        if ($dateTimeErrors === false) {
            return;
        }

        if (
            $dateTimeErrors['warning_count'] !== 0 ||
            $dateTimeErrors['error_count'] !== 0 ||
            checkdate(
                (int) $publicationDate->format('m'),
                (int) $publicationDate->format('d'),
                (int) $publicationDate->format('Y')
            )
        ) {
            throw new DomainRuleException($request, 422, 'Invalid book publication date.');
        }
    }

    public function validateDescription(ServerRequestInterface $request, string $description): void
    {
        if (! is_string($description)) {
            throw new DomainRuleException($request, 422, 'Invalid book description.');
        }
    }

    public function validateLanguage(ServerRequestInterface $request, string $language): void
    {
        if (! is_string($language)) {
            throw new DomainRuleException($request, 422, 'Invalid book language.');
        }

        if (preg_match('/^pt(?:-[A-Z]{2})?$/', $language) === false) {
            throw new DomainRuleException($request, 422, 'Invalid book language.');
        }
    }

    public function validateTotalPages(ServerRequestInterface $request, int $totalPages): void
    {
        if (! is_int($totalPages) || $totalPages <= 0) {
            throw new DomainRuleException($request, 422, 'Invalid number of pages.');
        }
    }

    public function validateStock(ServerRequestInterface $request, int $stock): void
    {
        if (! is_int($stock) || $stock < 0) {
            throw new DomainRuleException($request, 422, 'Invalid stock.');
        }
    }

    public function validateLocation(ServerRequestInterface $request, ?string $location): void
    {
        if (is_null($location)) {
            return;
        }

        if (! is_string($location) || strlen($location) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid location.');
        }
    }

    public function validateKeyword(ServerRequestInterface $request, ?string $keyword): void
    {
        if (is_null($keyword)) {
            return;
        }

        if (! is_string($keyword) || strlen($keyword) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid keyword.');
        }
    }

    public function validateCoverImage(ServerRequestInterface $request, ?string $coverImage): void
    {
        if (is_null($coverImage)) {
            return;
        }

        if (! is_string($coverImage) || strlen($coverImage) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid keyword.');
        }
    }

    public function validateEdition(ServerRequestInterface $request, ?string $edition): void
    {
        if (is_null($edition)) {
            return;
        }

        if (! is_string($edition) || strlen($edition) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid edition.');
        }
    }

    public function validatePrice(ServerRequestInterface $request, int $price): void
    {
        if (! is_int($price) || $price < 0) {
            throw new DomainRuleException($request, 422, 'Invalid price.');
        }
    }

    public function validateAcquisitionSource(ServerRequestInterface $request, ?string $acquisitionSource): void
    {
        if (is_null($acquisitionSource)) {
            return;
        }

        if (! is_string($acquisitionSource) || strlen($acquisitionSource) > 255) {
            throw new DomainRuleException($request, 422, 'Invalid acquisition source.');
        }
    }
}

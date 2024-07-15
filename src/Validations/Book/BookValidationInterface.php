<?php

declare(strict_types=1);

namespace Masoretic\Validations\Book;

use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface;

interface BookValidationInterface
{
    public function validateBookId(
        ServerRequestInterface $request,
        int $bookId
    ): void;

    public function validateTitle(
        ServerRequestInterface $request,
        string $title
    ): void;

    public function validateAuthor(
        ServerRequestInterface $request,
        string $author
    ): void;

    public function validateIsbn(
        ServerRequestInterface $request,
        string $isbn
    ): void;

    public function validatePublisher(
        ServerRequestInterface $request,
        string $publisher
    ): void;

    public function validatePublicationDate(
        ServerRequestInterface $request,
        DateTimeImmutable $publicationDate
    ): void;

    public function validateDescription(
        ServerRequestInterface $request,
        string $description
    ): void;

    public function validateLanguage(
        ServerRequestInterface $request,
        string $language
    ): void;

    public function validateTotalPages(
        ServerRequestInterface $request,
        int $totalPages
    ): void;

    public function validateStock(
        ServerRequestInterface $request,
        int $stock
    ): void;

    public function validateLocation(
        ServerRequestInterface $request,
        ?string $location
    ): void;

    public function validateKeyword(
        ServerRequestInterface $request,
        ?string $keyword
    ): void;

    public function validateCoverImage(
        ServerRequestInterface $request,
        ?string $coverImage
    ): void;

    public function validateEdition(
        ServerRequestInterface $request,
        ?string $edition
    ): void;

    public function validatePrice(
        ServerRequestInterface $request,
        int $price
    ): void;

    public function validateAcquisitionSource(
        ServerRequestInterface $request,
        ?string $acquisitionSource
    ): void;
}

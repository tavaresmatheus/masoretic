<?php

declare(strict_types=1);

namespace Masoretic\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240708151933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $booksTable = $schema->createTable('books');
        $booksTable->addColumn('book_id', 'bigint', ['autoincrement' => true]);
        $booksTable->addColumn('title', 'string', ['length' => 255, 'notnull' => true]);
        $booksTable->addColumn('authors', 'string', ['length' => 255, 'notnull' => true]);
        $booksTable->addColumn('isbn', 'string', ['length' => 13, 'notnull' => true]);
        $booksTable->addColumn('publisher', 'string', ['length' => 255, 'notnull' => true]);
        $booksTable->addColumn('publication_date', 'date', ['notnull' => true]);
        $booksTable->addColumn('description', 'string', ['length' => 255, 'notnull' => true]);
        $booksTable->addColumn('language', 'string', ['length' => 2, 'notnull' => true]);
        $booksTable->addColumn('total_pages', 'integer', ['notnull' => true]);
        $booksTable->addColumn('stock', 'integer', ['notnull' => true]);
        $booksTable->addColumn('location', 'string', ['length' => 255, 'notnull' => false]);
        $booksTable->addColumn('keywords', 'string', ['length' => 255, 'notnull' => false]);
        $booksTable->addColumn('cover_image', 'text', ['notnull' => false]);
        $booksTable->addColumn('edition', 'string', ['length' => 255, 'notnull' => false]);
        $booksTable->addColumn('price', 'integer', ['length' => 11, 'notnull' => true, 'default' => 0]);
        $booksTable->addColumn('acquisition_source', 'string', ['length' => 255, 'notnull' => false]);
        $booksTable->addColumn('deleted', 'boolean');

        $booksTable->addColumn(
            'created_at',
            'datetime',
            [
                'notnull' => true,
                'default' => $this->connection->getDatabasePlatform()
                    ->getCurrentTimestampSQL()
            ]
        );

        $datetimeType = \Doctrine\DBAL\Types\Type::getType('datetime');
        $booksTable->addColumn(
            'updated_at',
            'datetime',
            [
                'columnDefinition' => $datetimeType->lookupName($datetimeType) .
                    ' ON UPDATE ' .
                    $this->connection->getDatabasePlatform()
                        ->getCurrentTimestampSQL()
            ]
        );

        $booksTable->setPrimaryKey(['book_id']);
    }

    public function down(Schema $schema): void
    {
    }
}

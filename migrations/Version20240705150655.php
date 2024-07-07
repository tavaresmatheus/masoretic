<?php

declare(strict_types=1);

namespace Masoretic\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240705150655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $usersTable = $schema->createTable('books');
        $usersTable->addColumn('book_id', 'bigint', ['autoincrement' => true]);
        $usersTable->addColumn('title', 'string', ['length' => 255, 'notnull' => true]);
        $usersTable->addColumn('authors', 'string', ['length' => 255, 'notnull' => true]);
        $usersTable->addColumn('isbn', 'string', ['length' => 13, 'notnull' => true]);
        $usersTable->addColumn('publisher', 'string', ['length' => 255, 'notnull' => true]);
        $usersTable->addColumn('publication_date', 'date', ['notnull' => true]);
        $usersTable->addColumn('category', 'string', ['length' => 255, 'notnull' => true]);
        $usersTable->addColumn('description', 'string', ['length' => 255, 'notnull' => true]);
        $usersTable->addColumn('language', 'string', ['length' => 2, 'notnull' => true]);
        $usersTable->addColumn('total_pages', 'integer', ['notnull' => true]);
        $usersTable->addColumn('stock', 'integer', ['notnull' => true]);
        $usersTable->addColumn('location', 'string', ['length' => 255, 'notnull' => false]);
        $usersTable->addColumn('keywords', 'string', ['length' => 255, 'notnull' => false]);
        $usersTable->addColumn('cover_image', 'text', ['notnull' => false]);
        $usersTable->addColumn('edition', 'string', ['length' => 255, 'notnull' => false]);
        $usersTable->addColumn('price', 'integer', ['length' => 11, 'notnull' => true, 'default' => 0]);
        $usersTable->addColumn('acquisition_source', 'string', ['length' => 255, 'notnull' => false]);

        $usersTable->addColumn(
            'created_at',
            'datetime',
            [
                'notnull' => true,
                'default' => $this->connection->getDatabasePlatform()
                    ->getCurrentTimestampSQL()
            ]
        );

        $datetimeType = \Doctrine\DBAL\Types\Type::getType('datetime');
        $usersTable->addColumn(
            'updated_at',
            'datetime',
            [
                'columnDefinition' => $datetimeType->lookupName($datetimeType) .
                    ' ON UPDATE ' .
                    $this->connection->getDatabasePlatform()
                        ->getCurrentTimestampSQL()
            ]
        );

        $usersTable->setPrimaryKey(['book_id']);
    }

    public function down(Schema $schema): void
    {
    }
}

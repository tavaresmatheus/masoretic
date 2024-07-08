<?php

declare(strict_types=1);

namespace Masoretic\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240708153326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $booksCategoriesTable = $schema->createTable('books_categories');
        $booksCategoriesTable->addColumn('book_category_id', 'bigint', ['autoincrement' => true]);
        $booksCategoriesTable->addColumn('book_id', 'bigint', ['notnull' => true]);
        $booksCategoriesTable->addColumn('category_id', 'bigint', ['notnull' => true]);
        $booksCategoriesTable->addColumn('deleted', 'boolean');

        $booksCategoriesTable->addColumn(
            'created_at',
            'datetime',
            [
                'notnull' => true,
                'default' => $this->connection->getDatabasePlatform()
                    ->getCurrentTimestampSQL()
            ]
        );

        $datetimeType = \Doctrine\DBAL\Types\Type::getType('datetime');
        $booksCategoriesTable->addColumn(
            'updated_at',
            'datetime',
            [
                'columnDefinition' => $datetimeType->lookupName($datetimeType) .
                    ' ON UPDATE ' .
                    $this->connection->getDatabasePlatform()
                        ->getCurrentTimestampSQL()
            ]
        );

        $booksCategoriesTable->setPrimaryKey(['book_category_id']);
        $booksCategoriesTable->addForeignKeyConstraint('books', ['book_id'], ['book_id']);
        $booksCategoriesTable->addForeignKeyConstraint('categories', ['category_id'], ['category_id']);
    }

    public function down(Schema $schema): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240708151225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $categoriesTable = $schema->createTable('categories');
        $categoriesTable->addColumn('category_id', 'bigint', ['autoincrement' => true]);
        $categoriesTable->addColumn('name', 'string', ['length' => 255, 'notnull' => true]);

        $categoriesTable->addColumn(
            'created_at',
            'datetime',
            [
                'notnull' => true,
                'default' => $this->connection->getDatabasePlatform()
                    ->getCurrentTimestampSQL()
            ]
        );

        $datetimeType = \Doctrine\DBAL\Types\Type::getType('datetime');
        $categoriesTable->addColumn(
            'updated_at',
            'datetime',
            [
                'columnDefinition' => $datetimeType->lookupName($datetimeType) .
                    ' ON UPDATE ' .
                    $this->connection->getDatabasePlatform()
                        ->getCurrentTimestampSQL()
            ]
        );

        $categoriesTable->setPrimaryKey(['category_id']);
    }

    public function down(Schema $schema): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240517145830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $usersTable = $schema->createTable('users');
        $usersTable->addColumn('user_id', 'bigint', ['autoincrement' => true]);
        $usersTable->addColumn('name', 'string', ['length' => 255]);
        $usersTable->addColumn('password', 'string', ['length' => 255]);
        $usersTable->addColumn(
            'activation_hash', 'string', ['length' => 255, 'notnull' => true]
        );
        $usersTable->addColumn('active', 'boolean');
        $usersTable->addColumn('deleted', 'boolean');
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

        $usersTable->setPrimaryKey(['user_id']);
    }

    public function down(Schema $schema): void
    {
    }
}

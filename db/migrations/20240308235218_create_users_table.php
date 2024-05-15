<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table(
            'users',
            ['id' => false, 'primary_key' => ['user_id']]
        );
        $table->addColumn(
            'user_id',
            'uuid',
            ['default' => Literal::from('UUID()')]
        )
            ->addColumn('name', 'string', ['null' => false, 'limit' => 255])
            ->addColumn('email', 'string', ['null' => false, 'limit' => 255])
            ->addColumn('password', 'string', ['null' => false, 'limit' => 255])
            ->addColumn(
                'activation_hash',
                'string',
                ['null' => false, 'limit' => 32]
            )
            ->addColumn('active', 'boolean', ['default' => 0])
            ->addColumn('deleted', 'boolean', ['default' => 0])
            ->addTimestamps()
            ->create();
    }
}

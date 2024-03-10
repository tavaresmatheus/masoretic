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
            ->addColumn('name', 'text', ['null' => false])
            ->addColumn('email', 'text', ['null' => false])
            ->addColumn('password', 'text', ['null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('updated_at', 'datetime')
            ->addColumn('deleted', 'boolean', ['default' => 0])
            ->create();
    }
}

<?php

declare(strict_types=1);

namespace Masoretic\DBAL;

class Configurations
{
    protected array $databaseSettings;

    public function __construct()
    {
        $this->databaseSettings = [
            'dbname' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_ROOT_PASSWORD'),
            'host' => 'masoretic-mariadb',
            'driver' => 'pdo_mysql',
        ];
    }

    public function getDatabaseSettings(): array
    {
        return $this->databaseSettings;
    }
}

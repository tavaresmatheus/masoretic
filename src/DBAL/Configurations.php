<?php

declare(strict_types=1);

namespace Masoretic\DBAL;

use Dotenv\Dotenv;

class Configurations
{
    protected array $databaseSettings;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $this->databaseSettings = [
            'dbname' => $_ENV['MYSQL_DATABASE'],
            'user' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_ROOT_PASSWORD'],
            'host' => 'masoretic-mariadb',
            'driver' => 'pdo_mysql',
        ];
    }

    public function getDatabaseSettings(): array
    {
        return $this->databaseSettings;
    }
}

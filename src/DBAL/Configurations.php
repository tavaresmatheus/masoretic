<?php

declare(strict_types=1);

namespace Masoretic\DBAL;

class Configurations
{
    /**
     * @var array{dbname: string, user: string, password: string, host: string, driver: 'pdo_mysql'}
     */
    protected array $databaseSettings;

    public function __construct()
    {
        $this->databaseSettings = [
            'dbname' => is_string(getenv('MYSQL_DATABASE')) ? getenv('MYSQL_DATABASE') : '',
            'user' => is_string(getenv('MYSQL_USER')) ? getenv('MYSQL_USER') : '',
            'password' => is_string(getenv('MYSQL_ROOT_PASSWORD')) ? getenv('MYSQL_ROOT_PASSWORD') : '',
            'host' => 'masoretic-mariadb',
            'driver' => 'pdo_mysql',
        ];
    }

    /**
     * @return array{dbname: string, user: string, password: string, host: string, driver: 'pdo_mysql'}
     */
    public function getDatabaseSettings(): array
    {
        return $this->databaseSettings;
    }
}

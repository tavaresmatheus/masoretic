<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->safeLoad();

return DriverManager::getConnection([
    'dbname' => getenv('MYSQL_DATABASE'),
    'user' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'host' => 'masoretic-mariadb',
    'driver' => 'pdo_mysql',
]);
<?php

declare(strict_types=1);

namespace Masoretic\DBAL;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class Database
{
    protected Configurations $configurations;

    public function __construct(Configurations $configurations)
    {
        $this->configurations = $configurations;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        $connection = DriverManager::getConnection(
            $this->configurations->getDatabaseSettings()
        );

        return $connection->createQueryBuilder();
    }
}

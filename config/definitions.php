<?php

declare(strict_types=1);

use Masoretic\Controllers\HelloWorldController;
use Masoretic\DBAL\Configurations;
use Masoretic\DBAL\Database;

use function DI\create;

return [
    'HelloWorldController' => create(HelloWorldController::class),
    'Configurations' => create(Configurations::class),
    'Database' => create(Database::class),
];

<?php

declare(strict_types=1);

use Masoretic\Businesses\User\UserBusiness;
use Masoretic\Businesses\User\UserBusinessInterface;
use Masoretic\Controllers\UserController;
use Masoretic\DBAL\Configurations;
use Masoretic\DBAL\Database;
use Masoretic\Models\User;
use Masoretic\Repositories\User\UserRepository;
use Masoretic\Repositories\User\UserRepositoryInterface;

use function DI\autowire;
use function DI\create;

return [
    'UserController' => create(UserController::class),
    'Configurations' => create(Configurations::class),
    'Database' => create(Database::class),
    'User' => create(User::class),
    UserRepositoryInterface::class => autowire(UserRepository::class),
    UserBusinessInterface::class => autowire(UserBusiness::class),
];

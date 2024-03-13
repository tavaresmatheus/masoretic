<?php

declare(strict_types=1);

use Masoretic\Businesses\User\UserBusiness;
use Masoretic\Businesses\User\UserBusinessInterface;
use Masoretic\Controllers\HelloWorldController;
use Masoretic\DBAL\Configurations;
use Masoretic\DBAL\Database;
use Masoretic\Models\User;
use Masoretic\Repositories\User\UserRepository;
use Masoretic\Repositories\User\UserRepositoryInterface;

use function DI\create;

return [
    'HelloWorldController' => create(HelloWorldController::class),
    'Configurations' => create(Configurations::class),
    'Database' => create(Database::class),
    'User' => create(User::class),
    UserRepositoryInterface::class => create(UserRepository::class),
    UserBusinessInterface::class => create(UserBusiness::class),
];

<?php

declare(strict_types=1);

use Masoretic\Businesses\User\UserBusiness;
use Masoretic\Businesses\User\UserBusinessInterface;
use Masoretic\Controllers\UserController;
use Masoretic\DBAL\Configurations;
use Masoretic\DBAL\Database;
use Masoretic\Repositories\User\UserRepository;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Masoretic\Validations\User\UserValidation;
use Masoretic\Validations\User\UserValidationInterface;

use function DI\autowire;
use function DI\create;

return [
    'UserController' => create(UserController::class),
    'Configurations' => create(Configurations::class),
    'Database' => create(Database::class),
    UserRepositoryInterface::class => autowire(UserRepository::class),
    UserBusinessInterface::class => autowire(UserBusiness::class),
    UserValidationInterface::class => autowire(UserValidation::class),
];

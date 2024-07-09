<?php

declare(strict_types=1);

use Masoretic\Businesses\Authentication\AuthenticationBusiness;
use Masoretic\Businesses\Authentication\AuthenticationBusinessInterface;
use Masoretic\Businesses\Category\CategoryBusiness;
use Masoretic\Businesses\Category\CategoryBusinessInterface;
use Masoretic\Businesses\User\UserBusiness;
use Masoretic\Businesses\User\UserBusinessInterface;
use Masoretic\Controllers\AuthenticationController;
use Masoretic\Controllers\CategoryController;
use Masoretic\Controllers\UserController;
use Masoretic\DBAL\Configurations;
use Masoretic\DBAL\Database;
use Masoretic\Repositories\Category\CategoryRepository;
use Masoretic\Repositories\Category\CategoryRepositoryInterface;
use Masoretic\Repositories\User\UserRepository;
use Masoretic\Repositories\User\UserRepositoryInterface;
use Masoretic\Services\Email\EmailService;
use Masoretic\Services\Email\EmailServiceInterface;
use Masoretic\Validations\Category\CategoryValidation;
use Masoretic\Validations\Category\CategoryValidationInterface;
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
    'AuthenticationController' => create(AuthenticationController::class),
    AuthenticationBusinessInterface::class => autowire(
        AuthenticationBusiness::class
    ),
    EmailServiceInterface::class => autowire(EmailService::class),
    'CategoryController' => create(CategoryController::class),
    CategoryRepositoryInterface::class => autowire(CategoryRepository::class),
    CategoryBusinessInterface::class => autowire(CategoryBusiness::class),
    CategoryValidationInterface::class => autowire(CategoryValidation::class)
];

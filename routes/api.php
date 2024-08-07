<?php

declare(strict_types=1);

use Masoretic\Controllers\AuthenticationController;
use Masoretic\Controllers\BookController;
use Masoretic\Controllers\CategoryController;
use Masoretic\Controllers\UserController;
use Masoretic\Middlewares\AuthenticationMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->group('/users', function (RouteCollectorProxy $group) {
        $group->get('/{id}', UserController::class . ':showUser');
        $group->get('', UserController::class . ':listUsers');
        $group->patch('/{id}', UserController::class . ':updateUser');
        $group->delete('/{id}', UserController::class . ':deleteUser');
    })->add(new AuthenticationMiddleware());

    $group->group('/categories', function (RouteCollectorProxy $group) {
        $group->post('', CategoryController::class . ':createCategory');
        $group->get('', CategoryController::class . ':listCategories');
        $group->get('/{id}', CategoryController::class . ':showCategory');
        $group->patch('/{id}', CategoryController::class . ':updateCategory');
        $group->delete('/{id}', CategoryController::class . ':deleteCategory');
    })->add(new AuthenticationMiddleware());

    $group->group('/books', function (RouteCollectorProxy $group) {
        $group->post('', BookController::class . ':createBook');
    })->add(new AuthenticationMiddleware());

    $group->post('/login', AuthenticationController::class . ':authenticate');
    $group->post('/register', AuthenticationController::class . ':register');
    $group->post(
        '/confirm/{activationHash}',
        AuthenticationController::class . ':emailConfirmation'
    );
});

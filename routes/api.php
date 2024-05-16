<?php

declare(strict_types=1);

use Masoretic\Controllers\AuthenticationController;
use Masoretic\Controllers\UserController;
use Masoretic\Middlewares\AuthenticationMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->group('/users', function (RouteCollectorProxy $group) {
        $group->get('/{id}', UserController::class . ':showUser');
        $group->get('/', UserController::class . ':listUsers');
        $group->patch('/{id}', UserController::class . ':updateUser');
        $group->delete('/{id}', UserController::class . ':deleteUser');
    })->add(new AuthenticationMiddleware());

    $group->post('/login', AuthenticationController::class . ':authenticate');
    $group->post('/register', AuthenticationController::class . ':register');
    $group->post(
        '/confirm/{activationHash}',
        AuthenticationController::class . ':emailConfirmation'
    );
});

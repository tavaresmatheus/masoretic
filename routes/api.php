<?php

declare(strict_types=1);

use Masoretic\Controllers\UserController;
use Slim\Routing\RouteCollectorProxy;

$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('/register', UserController::class . ':registerUser');
    $group->get('/{id}', UserController::class . ':showUser');
    $group->get('/', UserController::class . ':listUsers');
    $group->put('/{id}', UserController::class . ':updateUser');
    $group->delete('/{id}', UserController::class . ':deleteUser');
});

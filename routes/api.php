<?php

declare(strict_types=1);

use Masoretic\Controllers\UserController;
use Slim\Routing\RouteCollectorProxy;

$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('/register', UserController::class . ':registerUser');
    $group->get('/{id}',UserController::class . ':showUser');
    $group->get('/',UserController::class . ':listUsers');
});

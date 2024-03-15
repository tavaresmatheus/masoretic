<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Masoretic\Middlewares\ContentTypeJsonMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/definitions.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new ContentTypeJsonMiddleware());
$app->addErrorMiddleware(true, true, true);

require_once __DIR__ . '/../routes/api.php';

$app->run();

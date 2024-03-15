<?

declare(strict_types=1);

use Masoretic\Controllers\UserController;

$app->get('/', UserController::class . ':register');

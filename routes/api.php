<?

declare(strict_types=1);

use Masoretic\Controllers\HelloWorldController;

$app->get('/', HelloWorldController::class . ':helloWorld');

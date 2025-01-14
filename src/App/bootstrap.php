<?php

declare(strict_types=1);

// include __DIR__ . "/../Framework/App.php";
require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Controllers\{HomeController, AboutController};
use App\Config\Paths;

$app = new App(Paths::SOURCE . "app/container-definitions.php");

//routes
$app->get('/', [HomeController::class, 'home']);

$app->get('/about', [AboutController::class, 'about']);

return $app;

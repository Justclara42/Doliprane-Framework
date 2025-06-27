<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/env.php';

use App\Core\App;

session_start();

$app = new App();
$app->run();

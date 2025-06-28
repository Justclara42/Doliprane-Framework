<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/env.php';

use App\Core\App;
use App\Core\Eloquent;

session_start();

Eloquent::boot();
$app = new App();
$app->run();

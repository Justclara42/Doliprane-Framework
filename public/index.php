<?php
define('ROOT', dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/env.php';

use App\Core\App;
use App\Core\Eloquent;
use App\Core\DatabaseManager;

session_start();
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
DatabaseManager::init();

Eloquent::boot();
$app = new App();
$app->run();

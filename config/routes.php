<?php
use App\Controllers\HomeController;
use App\Controllers\PageController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/docs', [PageController::class, 'docs']);

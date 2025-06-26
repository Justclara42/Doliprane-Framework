<?php
const ROUTES = [
    '/' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'home'
    ],
    'home' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'home'
    ],
    'contact' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'contact'
    ],
    'blog' => [
        'controller' => App\Controller\ArticleController::class,
        'method' => 'index'
    ],
    'add_article' => [
        'controller' => App\Controller\ArticleController::class,
        'method' => 'add'
    ],
];


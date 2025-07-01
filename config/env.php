<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return ROOT . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

define('DB_CONNECTION', $_ENV['DB_CONNECTION'] ?? 'sqlite');
define('DB_DATABASE', $_ENV['DB_DATABASE'] ?? 'database.sqlite');
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');

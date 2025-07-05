<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

require_once ROOT . '/bootstrap/helpers.php';
require ROOT . '/vendor/autoload.php';
require ROOT . '/config/env.php';

use App\Core\App;
use App\Core\View;
use App\Core\Lang;
use App\Core\Eloquent;
use App\Core\DatabaseManager;
use App\Core\ErrorHandler;
use App\Debug\DebugManager;

if (PHP_SAPI !== 'cli') { // Cela exclut l'exécution via CLI (tests ou commandes console)
    session_start();

    // Lancer l'application en mode HTTP
    $app = new App();
    $response = $app->run();
    echo $response;
} else {
    // En CLI, évitez les parties liées à la vue ou HTTP
}
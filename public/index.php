<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/bootstrap/helpers.php';
require ROOT . '/vendor/autoload.php';
require ROOT . '/config/env.php';

use App\Core\App;
use App\Core\View;
use App\Core\Lang;
use App\Core\Eloquent;
use App\Core\DatabaseManager;
use App\Controllers\ErrorController;
use App\Debug\DebugManager;

session_start();

// Langue
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Gestion des erreurs
if (is_dev()) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Initialisations
Lang::setLocale($_SESSION['lang'] ?? 'fr_FR');
DatabaseManager::init();
Eloquent::boot();
View::init();

// Handlers
set_exception_handler(function ($e) {
    $GLOBALS['last_exception'] = $e;
    if (is_dev()) {
        echo "<!-- Exception attrapée (DebugBar active) -->";
    }
    (new ErrorController())->show(500, $e->getMessage());
});

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $GLOBALS['last_exception'] = new ErrorException(
            $error['message'],
            0,
            $error['type'],
            $error['file'],
            $error['line']
        );
        (new ErrorController())->show(500, $error['message']);
    }
});

// Exécute l'application (déclenche les routes et les contrôleurs)
// ✅ Important : ici on remplit $_REQUEST['__controller_called'] dans Router
$app = new App();
$app->run();

// Collecte les données de debug après que la réponse ait été envoyée
$debug = new DebugManager();
$debug->collectAll();
$debugData = $debug->getCollectedData();

// Affiche la DebugBar si en développement
if (is_dev()) {
    extract($debugData); // rend $time, $memory, $php, $route, etc. disponibles
    include ROOT . '/templates/components/debugbar.php';
}

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
use App\Core\DebugBar;
use App\Controllers\ErrorController;

session_start();
// 🧪 Boot DebugBar (enregistre début du script)
if (is_dev()) {
    DebugBar::boot();
}

// 🌍 Langue sélectionnée
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}


// 🧠 Gestion erreurs & exceptions
if (is_dev()) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ⚠️ Initialiser View avant toute erreur potentielle
View::init();

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

// 🚀 Initialisation des autres services
Lang::setLocale($_SESSION['lang'] ?? 'fr_FR');
DatabaseManager::init();
Eloquent::boot();

// 🎬 Lancer l'application
$app = new App();
$app->run();

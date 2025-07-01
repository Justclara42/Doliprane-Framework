<?php
define('ROOT', dirname(__DIR__));

require_once ROOT . '/bootstrap/helpers.php'; // ✅ Fonctions globales accessibles partout

require ROOT . '/vendor/autoload.php';
require ROOT . '/config/env.php';

use App\Core\App;
use App\Core\View;
use App\Core\Lang;
use App\Core\Eloquent;
use App\Core\DatabaseManager;

session_start();

// Langue sélectionnée
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Initialisation des services
Lang::setLocale($_SESSION['lang'] ?? 'fr_FR');
View::init();
DatabaseManager::init();
Eloquent::boot();


// Lancer l'application
$app = new App();
$app->run();


// Affichage erreurs en dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

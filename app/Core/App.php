<?php
namespace App\Core;

class App {
    public Router $router;

    public function __construct() {
        $this->router = new Router(__DIR__ . '/../../config/routes.php');
    }

    public function run() {
        ob_start(); // Évite les erreurs d'en-têtes
        $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        ob_end_flush();
    }
}

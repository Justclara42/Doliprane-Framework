<?php
namespace App\Core;

class App {
    public Router $router;

    public function __construct() {
        $this->router = new Router();

        // Ajout temporaire d'une variable globale accessible dans routes.php
        $router = $this->router;
        require __DIR__ . '/../../routes.php';
    }

    public function run() {
        $this->router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }
}

<?php

namespace App\Core;

use App\Core\Lang;
use App\Core\Router;
use App\Controllers\ErrorController;

class App
{
    protected Router $router;

    public function __construct()
    {
        // Initialisation du router
        $this->router = new Router(ROOT . '/config/routes.php');
    }

    public function run(): void
    {
        try {
            ob_start(); // Pour éviter les erreurs d'en-têtes
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
            ob_end_flush();
        } catch (\Throwable $e) {
            ob_end_clean(); // Nettoie le buffer s’il y a une exception
            $this->handleException($e);
        }
    }

    protected function handleException(\Throwable $e): void
    {
        if (env('APP_ENV') === 'dev') {
            http_response_code(500);
            echo "<h1>Erreur</h1>";
            echo "<p><strong>Message :</strong> {$e->getMessage()}</p>";
            echo "<pre>{$e->getTraceAsString()}</pre>";
        } else {
            http_response_code(500);
            (new ErrorController())->show(500);
        }
    }
}

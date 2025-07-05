<?php

namespace App\Core;

use App\Core\Router;
use App\Controllers\ErrorController;
use App\Debug\ErrorLogger;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router(ROOT . '/config/routes.php');
    }

    public function run(): string
    {
        try {
            ob_start();
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
            return ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            return $this->handleException($e);
        }
    }

    protected function handleException(\Throwable $e): string
    {
        http_response_code(500);

        // 👇 Ajout manuel du log de l'erreur ici
        ErrorLogger::logError(
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        ob_start();
        (new ErrorController())->show(500, $e->getMessage(), $e->getTraceAsString());
        return ob_get_clean();
    }

//    protected function handleException(\Throwable $e): string
//    {
//        http_response_code(500);
//        ob_start();
//        (new ErrorController())->show(500, $e->getMessage(), $e->getTraceAsString());
//        return ob_get_clean();
//    }
}

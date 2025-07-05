<?php

namespace App\Controllers;

use App\Core\View;
use App\Debug\DebugManager;

class ErrorController
{
    public function show(int $code = 500, string $message = '', string $trace = ''): void
    {
        // Récupération exception globale si besoin
        if (empty($trace) && isset($GLOBALS['__caught_exception'])) {
            $trace = $GLOBALS['__caught_exception']->getTraceAsString();
            if (empty($message)) {
                $message = $GLOBALS['__caught_exception']->getMessage();
            }
        }

        // Injecter debugbar si DEV
        if (is_dev()) {
            $debug = new DebugManager();
            $debug->collectAll();
            $debugData = $debug->getCollectedData();

            foreach ($debugData as $key => $value) {
                View::setGlobal($key, $value);
            }

            ob_start();
            extract($debugData);
            include ROOT . '/templates/components/debugbar.php';
            $debugbarHtml = ob_get_clean();
            View::setGlobal('debugbar', $debugbarHtml);
        }

        // Affichage erreur
        View::render(
            file_exists(ROOT . "/resources/views/errors/error.dtf") ? "errors.$code" : "errors.error",
            [
                'code' => $code,
                'message' => is_dev() ? $message : "Une erreur est survenue.",
                'trace' => is_dev() ? $trace : '',
                'devMode' => is_dev(),
            ]
        );
    }
}

<?php

namespace App\Controllers;

use App\Core\View;

class ErrorController
{
    public function show(int $code = 500, string $message = '', string $trace = ''): void
    {
        if (empty($trace) && isset($GLOBALS['last_exception'])) {
            $trace = $GLOBALS['last_exception']->getTraceAsString();
            if (empty($message)) {
                $message = $GLOBALS['last_exception']->getMessage();
            }
        }

        if (!is_dev()) {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Code: $code | Message: $message\n";
            @file_put_contents(ROOT . '/storage/logs/errors.log', $logMessage, FILE_APPEND);
        }

        $data = [
            'code' => $code,
            'message' => is_dev() ? $message : "Une erreur est survenue.",
            'trace' => is_dev() ? $trace : '',
            'devMode' => is_dev(),
        ];

        View::render(
            file_exists(ROOT . "/resources/views/errors/$code.dtf") ? "errors.$code" : "errors.error",
            $data
        );
    }
}

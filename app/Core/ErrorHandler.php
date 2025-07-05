<?php
namespace App\Core;

use App\Debug\ErrorLogger;
use App\Core\View;
use App\Controllers\ErrorController;

class ErrorHandler
{
    public static function handleException(\Throwable $e): void
    {
        ErrorLogger::logError(
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        $GLOBALS['__caught_exception'] = $e;

        http_response_code(500);
        (new ErrorController())->show(500, $e->getMessage(), $e->getTraceAsString());
        exit;
    }

    public static function handleError($errno, $errstr, $errfile, $errline): void
    {
        if (in_array($errno, [E_WARNING, E_USER_WARNING, E_NOTICE, E_USER_NOTICE])) {
            ErrorLogger::logWarning($errstr, $errfile, $errline);
        } else {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $exception = new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );
            $GLOBALS['__caught_exception'] = $exception;
            ErrorLogger::logError(
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString()
            );
        }
    }
}

<?php
namespace App\Core;

class ErrorHandler
{
    public static function handleException(\Throwable $e): void
    {
        http_response_code(500);
        View::render('errors.error', [
            'code'    => 500,
            'message'=> $e->getMessage(),
            'trace'  => getenv('APP_ENV') === 'dev' ? $e->getTraceAsString() : null
        ]);
        exit;
    }

    public static function handleError($errno, $errstr, $errfile, $errline): void
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            http_response_code(500);
            View::render('errors.error', [
                'code'    => 500,
                'message'=> $error['message'],
                'trace'  => getenv('APP_ENV') === 'dev'
                    ? "{$error['file']} line {$error['line']}"
                    : null
            ]);
            exit;
        }
    }
}

<?php

namespace App\Debug;

class ErrorLogger
{
    protected static array $errors = [];
    protected static array $warnings = [];

    public static function logError(string $message, string $file, int $line, string $trace = ''): void
    {
        $logDir = ROOT . '/storage/logs/';
        $logFile = $logDir . 'errors.log';

        try {
            // Vérifiez que le dossier existe et peut être écrit
            if (!is_dir($logDir)) {
                if (!mkdir($logDir, 0777, true) && !is_dir($logDir)) {
                    return; // Si on n'arrive pas à créer le dossier, on quitte
                }
            }

            // Vérifiez que le fichier est accessible en écriture
            if (!is_writable($logDir) || (file_exists($logFile) && !is_writable($logFile))) {
                return; // Si on ne peut pas écrire, on quitte silencieusement
            }

            // Écrivez dans les logs
            $logMessage = "[" . date('Y-m-d H:i:s') . "] $message in $file on line $line\n" . $trace . "\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        } catch (\Exception $e) {
            // Ignore les exceptions pour éviter d'autres erreurs
            if (is_dev()) {
                echo "[LOGGING ERROR] " . $e->getMessage();
            }
        }
    }

    public static function logWarning(string $message, string $file, int $line): void
    {
        self::$warnings[] = [
            'message' => $message,
            'file'    => $file,
            'line'    => $line,
        ];
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function getWarnings(): array
    {
        return self::$warnings;
    }
}
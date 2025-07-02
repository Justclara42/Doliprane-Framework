<?php

declare(strict_types=1);

namespace App\Core;

class DebugBar
{
    protected static array $queries = [];
    protected static float $startTime = 0.0;
    protected static int $startMemory = 0;
    protected static array $phpErrors = [];

    public static function boot(): void
    {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage(true);

        // Capture les erreurs non fatales
        set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): void {
            $type = match ($errno) {
                E_ERROR             => 'E_ERROR',
                E_WARNING           => 'E_WARNING',
                E_PARSE             => 'E_PARSE',
                E_NOTICE            => 'E_NOTICE',
                E_CORE_ERROR        => 'E_CORE_ERROR',
                E_CORE_WARNING      => 'E_CORE_WARNING',
                E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
                E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
                E_USER_ERROR        => 'E_USER_ERROR',
                E_USER_WARNING      => 'E_USER_WARNING',
                E_USER_NOTICE       => 'E_USER_NOTICE',
                E_STRICT            => 'E_STRICT',
                E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                default             => 'UNKNOWN',
            };

            self::$phpErrors[] = [
                'type' => $type,
                'message' => $errstr,
                'file' => $errfile,
                'line' => $errline,
            ];
        });
    }

    public static function logQuery(string $sql, array $bindings = [], float $time = 0.0): void
    {
        // Remplacement sécurisé des placeholders ?
        foreach ($bindings as $binding) {
            $binding = is_numeric($binding) ? $binding : "'" . addslashes((string) $binding) . "'";
            $sql = preg_replace('/\?/', $binding, $sql, 1);
        }

        self::$queries[] = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time' => number_format($time, 2),
        ];
    }

    public static function getSummary(): array
    {
        $executionTime = (self::$startTime > 0)
            ? number_format((microtime(true) - self::$startTime) * 1000, 2)
            : '?';

        $memoryUsed = (self::$startMemory > 0)
            ? number_format((memory_get_usage(true) - self::$startMemory) / 1024 / 1024, 2)
            : '0.00';

        return [
            'time_ms' => $executionTime,
            'memory' => $memoryUsed . ' MB',
            'queries' => self::$queries,
            'errors' => self::$phpErrors,
        ];
    }
}

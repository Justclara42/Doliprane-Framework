<?php
namespace App\Core;

class DatabaseManager
{
    public static function getConfig(): array
    {
        $driver = $_ENV['DB_CONNECTION'] ?? 'sqlite';
        $config = [
            'driver' => $driver,
            'prefix' => '',
        ];

        switch ($driver) {
            case 'sqlite':
                $config['database'] = ROOT . '/database/' . ($_ENV['DB_DATABASE'] ?? 'database.sqlite');
                break;

            case 'mysql':
                $config = array_merge($config, [
                    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                    'database' => $_ENV['DB_DATABASE'],
                    'username' => $_ENV['DB_USERNAME'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'port' => $_ENV['DB_PORT'] ?? 3306,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]);
                break;

            case 'pgsql':
                $config = array_merge($config, [
                    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                    'database' => $_ENV['DB_DATABASE'],
                    'username' => $_ENV['DB_USERNAME'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'port' => $_ENV['DB_PORT'] ?? 5432,
                    'charset' => 'utf8',
                    'schema' => 'public',
                ]);
                break;
        }

        return [
            'default' => $driver,
            'connections' => [
                $driver => $config,
            ],
        ];
    }

    public static function init(): void
    {
        // Rien à faire ici
    }
}

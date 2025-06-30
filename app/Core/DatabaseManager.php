<?php
namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseManager
{
    public static function init(): void
    {
        $capsule = new Capsule;

        $driver = $_ENV['DB_CONNECTION'] ?? 'mysql';
        $database = $_ENV['DB_DATABASE'] ?? 'doliprane';

        if ($driver === 'sqlite') {
            // Chemin absolu vers le fichier .sqlite
            $databasePath = realpath(__DIR__ . '/../../database/' . $database . '.sqlite');

            if (!$databasePath) {
                // Si le fichier n'existe pas encore
                $databasePath = __DIR__ . '/../../database/' . $database . '.sqlite';
            }

            $capsule->addConnection([
                'driver'   => 'sqlite',
                'database' => $databasePath,
                'prefix'   => '',
            ]);
        } else {
            $capsule->addConnection([
                'driver'    => $driver,
                'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
                'port'      => $_ENV['DB_PORT'] ?? '3306',
                'database'  => $database,
                'username'  => $_ENV['DB_USERNAME'] ?? 'root',
                'password'  => $_ENV['DB_PASSWORD'] ?? '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);
        }

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

<?php
namespace App\Commands;

class MakeDatabaseCommand
{
    public function handle(array $args = []): void
    {
        $dbName = $args[0] ?? 'database.sqlite';
        $envPath = ROOT . '/.env';
        $databaseDir = ROOT . '/database';
        $dbPath = "$databaseDir/$dbName";

        if (!is_dir($databaseDir)) {
            mkdir($databaseDir, 0777, true);
        }

        if (!file_exists($dbPath)) {
            touch($dbPath);
            echo "✅ Fichier SQLite créé : $dbPath\n";
        } else {
            echo "ℹ️ Fichier déjà existant : $dbPath\n";
        }

        // Mise à jour .env
        $env = file_get_contents($envPath);
        $env = preg_replace('/^DB_DATABASE=.*/m', "DB_DATABASE={$dbName}", $env);
        $env = preg_replace('/^DB_CONNECTION=.*/m', "DB_CONNECTION=sqlite", $env);

        if (!str_contains($env, "DB_DATABASE=")) {
            $env .= "\nDB_DATABASE={$dbName}";
        }

        if (!str_contains($env, "DB_CONNECTION=")) {
            $env .= "\nDB_CONNECTION=sqlite";
        }

        file_put_contents($envPath, $env);
        echo "✅ .env mis à jour avec DB_CONNECTION=sqlite et DB_DATABASE={$dbName}\n";
    }
}

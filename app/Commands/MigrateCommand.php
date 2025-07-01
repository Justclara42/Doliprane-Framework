<?php

namespace App\Commands;

use App\Core\DatabaseManager;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('migrate:run')
            ->setDescription('Exécute toutes les migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Initialise la connexion à la base
        DatabaseManager::init();

        $migrationsDir = __DIR__ . '/../../database/migrations';
        $migrationFiles = glob("$migrationsDir/*.php");

        if (empty($migrationFiles)) {
            $output->writeln("<comment>⚠️ Aucune migration trouvée dans $migrationsDir</comment>");
            return Command::SUCCESS;
        }

        foreach ($migrationFiles as $file) {
            $filename = basename($file);
            $tableName = $this->extractTableNameFromFilename($filename);

            if (Capsule::schema()->hasTable($tableName)) {
                $output->writeln("<comment>⚠️ La table '$tableName' existe déjà. Migration ignorée ($filename).</comment>");
                continue;
            }

            $migration = require $file;

            if (is_object($migration) && method_exists($migration, 'up')) {
                $migration->up();
                $output->writeln("<info>✅ Migration exécutée : $filename</info>");
            } else {
                $output->writeln("<error>❌ Erreur : la migration $filename n'est pas valide.</error>");
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Déduit le nom de la table à partir du nom du fichier
     * Ex: 2025_06_30_123456_create_users_table.php → users
     */
    private function extractTableNameFromFilename(string $filename): string
    {
        // Recherche "create_xxx_table"
        if (preg_match('/create_(.+)_table/', $filename, $matches)) {
            return $matches[1];
        }

        return ''; // Par sécurité si non détecté
    }
}

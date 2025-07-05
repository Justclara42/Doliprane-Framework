<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use App\Core\DatabaseManager;
use Symfony\Component\Console\Attribute\AsCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:factory',
    description: 'Crée un fichier factory dans /database/factories.',
)]
class MakeFactoryCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de la factory (ex: UserFactory)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $filePath = ROOT . "/database/factories/{$name}.php";
        $stubPath = ROOT . '/stubs/Factory.stub';

        $this->ensureDirectory(dirname($filePath), $io, true);

        if (file_exists($filePath)) {
            $io->warning("Le fichier factory existe déjà : {$filePath}");
            return BaseCommand::SUCCESS;
        }

        if (!file_exists($stubPath)) {
            $io->error("Le stub de factory est introuvable.");
            return BaseCommand::FAILURE;
        }

        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{ className }}', $name, $stub);

        try {
            // Récupération des colonnes de la table associée
            $baseName = strtolower(str_replace('Factory', '', $name)); // Supprime le suffixe "Factory"
            $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $baseName)) . 's'; // Ajoute le suffixe 's' pour les tables au pluriel

            $columns = $this->getTableColumns($tableName);

            if (empty($columns)) {
                $io->warning("Impossible de détecter des colonnes dans la table '{$tableName}'. La factory sera vide.");
                return BaseCommand::SUCCESS;
            }

            $attributes = array_map(function ($column) {
                if ($column['name'] === 'id') {
                    // Ignorer les champs primaires auto-incrémentés
                    return null;
                }

                $fakerValue = $this->mapTypeToFaker($column['type']);
                return "            '{$column['name']}' => {$fakerValue},";
            }, $columns);

            // Supprimer les entrées nulles (champs ignorés comme 'id')
            $attributes = array_filter($attributes);

            $stub = str_replace('// Columns Placeholder', implode(PHP_EOL, $attributes), $stub);
            file_put_contents($filePath, $stub);

            $io->success("Factory créée : {$filePath}");
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return BaseCommand::FAILURE;
        }

        return BaseCommand::SUCCESS;
    }

private function getTableColumns(string $tableName): array
{
    $config = DatabaseManager::getConfig();
    $driver = $config['default']; // Driver (sqlite, mysql, pgsql)
    $dsn = $config['connections'][$driver]['database'] ?? null;

    try {
        $db = match ($driver) {
            'sqlite' => new \PDO("sqlite:" . $dsn),
            'mysql' => new \PDO(
                "mysql:host={$config['connections'][$driver]['host']};dbname={$dsn};charset={$config['connections'][$driver]['charset']}",
                $config['connections'][$driver]['username'],
                $config['connections'][$driver]['password']
            ),
            'pgsql' => new \PDO(
                "pgsql:host={$config['connections'][$driver]['host']};dbname={$dsn}",
                $config['connections'][$driver]['username'],
                $config['connections'][$driver]['password']
            ),
            default => throw new \InvalidArgumentException("Driver '{$driver}' non supporté."),
        };

        $query = match ($driver) {
            'sqlite' => "PRAGMA table_info('{$tableName}')",
            'mysql' =>
                "SELECT COLUMN_NAME as name, DATA_TYPE as type 
                 FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_NAME = '{$tableName}' 
                 AND TABLE_SCHEMA = '{$config['connections']['mysql']['database']}'",
            'pgsql' =>
                "SELECT column_name as name, data_type as type 
                 FROM information_schema.columns 
                 WHERE table_name = '{$tableName}'",
            default => throw new InvalidArgumentException("Impossible de récupérer les colonnes pour le driver {$driver}."),
        };

        $stmt = $db->query($query);

        if (!$stmt) {
            throw new \InvalidArgumentException("Impossible d'exécuter la requête pour obtenir les colonnes de '{$tableName}' avec le driver '{$driver}'.");
        }

        $columns = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $columns[] = [
                'name' => $row['name'],
                'type' => $this->mapSqlTypeToGenericType($row['type']),
            ];
        }

        return $columns;
    } catch (\PDOException $e) {
        throw new \InvalidArgumentException("Erreur de connexion ({$driver}) : " . $e->getMessage());
    }
}
private function mapSqlTypeToGenericType(string $sqlType): string
{
    $sqlType = strtolower($sqlType);

    if (str_contains($sqlType, 'int')) {
        return 'int';
    }

    if (in_array($sqlType, ['varchar', 'text', 'char', 'character varying'])) {
        return 'string';
    }

    if (in_array($sqlType, ['datetime', 'timestamp'])) {
        return 'datetime';
    }

    if (in_array($sqlType, ['date'])) {
        return 'date';
    }

    if (str_contains($sqlType, 'real') || str_contains($sqlType, 'float') || str_contains($sqlType, 'double') || $sqlType === 'numeric') {
        return 'float';
    }

    if (in_array($sqlType, ['boolean', 'bool'])) {
        return 'bool';
    }

    return 'string'; // Type par défaut
}
private function mapTypeToFaker(string $type): string
{
    return match ($type) {
        'int' => 'rand(1, 100)',
        'string' => '$faker->word()',
        'text' => '$faker->text()',
        'date' => '$faker->dateTime()->format(\'Y-m-d\')',
        'datetime' => '$faker->dateTime()->format(\'Y-m-d H:i:s\')',
        'bool' => '$faker->boolean()',
        'float' => 'rand(1, 100) / 10', // Exemple d'une valeur float entre 0.1 et 10
        default => "'default_value'", // Valeur par défaut si aucun mapping n'existe
    };
}
}
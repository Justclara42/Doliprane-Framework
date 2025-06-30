<?php
namespace App\Commands;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:database')
            ->setDescription('Crée la base de données définie dans .env (MySQL, PostgreSQL ou SQLite)')
            ->addArgument('name', InputArgument::OPTIONAL, 'Nom personnalisé de la base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $envPath = __DIR__ . '/../../';
        $dotenv = Dotenv::createImmutable($envPath);
        $dotenv->load();

        $connection = $_ENV['DB_CONNECTION'] ?? 'mysql';
        $argName = $input->getArgument('name');
        $envName = $_ENV['DB_DATABASE'] ?? 'app';
        $dbName = $argName ?: $envName;

        try {
            if ($connection === 'sqlite') {
                $dbName = str_replace('.sqlite', '', $dbName);
                $path = $envPath . "database/{$dbName}.sqlite";

                if (!file_exists($path)) {
                    touch($path);
                    $output->writeln("<info>Fichier SQLite créé : {$path}</info>");
                } else {
                    $output->writeln("<comment>Le fichier SQLite existe déjà : {$path}</comment>");
                }

            } else {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $user = $_ENV['DB_USERNAME'] ?? 'root';
                $pass = $_ENV['DB_PASSWORD'] ?? '';

                $dsn = "$connection:host=$host;port=$port";

                $pdo = new PDO($dsn, $user, $pass);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
                $output->writeln("<info>Base de données créée (ou existante) : $dbName</info>");
            }

            // Si un nom personnalisé a été fourni : mise à jour du .env
            if ($argName) {
                $envFile = $envPath . '.env';
                $envContent = file_get_contents($envFile);
                $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE=$dbName", $envContent);
                file_put_contents($envFile, $envContent);
                $output->writeln("<info>.env mis à jour : DB_DATABASE=$dbName</info>");
            }

            return Command::SUCCESS;

        } catch (PDOException $e) {
            $output->writeln("<error>Erreur PDO : {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}

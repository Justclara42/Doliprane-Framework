<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Database\Capsule\Manager as DB;

#[AsCommand(
    name: 'factory:apply',
    description: 'Applique une factory pour insérer des données dans la base de données.'
)]
class FactoryApplyCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('factory', InputArgument::REQUIRED, 'Nom de la factory (ex: UserFactory)')
            ->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Nombre d\'enregistrements à insérer', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $factoryName = $input->getArgument('factory');
        $factoryFile = ROOT . "/database/factories/{$factoryName}.php";

        if (!file_exists($factoryFile)) {
            $io->error("La factory '{$factoryName}' est introuvable à l'emplacement : {$factoryFile}");
            return BaseCommand::FAILURE;
        }

        require_once $factoryFile;

        if (!class_exists($factoryName)) {
            $io->error("La classe '{$factoryName}' n'existe pas dans le fichier : {$factoryFile}");
            return BaseCommand::FAILURE;
        }

        $factory = new $factoryName();
        if (!method_exists($factory, 'create')) {
            $io->error("La méthode 'create' est introuvable dans la factory '{$factoryName}'.");
            return BaseCommand::FAILURE;
        }

        $count = (int)$input->getOption('count');
        $io->text("Application de la factory : {$factoryName} ({$count} enregistrements).");

        try {
            for ($i = 0; $i < $count; $i++) {
                $data = $factory->create();

                // Nom de la table correspondante (ex. "users" pour "UserFactory")
                $tableName = strtolower(str_replace('Factory', '', $factoryName)) . 's';

                DB::table($tableName)->insert($data);
            }

            $io->success("{$count} enregistrements ont été insérés dans la table.");
        } catch (\Throwable $e) {
            $io->error("Erreur lors de l'insertion : " . $e->getMessage());
            return BaseCommand::FAILURE;
        }

        return BaseCommand::SUCCESS;
    }
}
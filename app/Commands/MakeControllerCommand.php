<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    /**
     * Configure the command options and arguments.
     */
    protected function configure(): void
    {
        $this
            ->setName('make:controller')
            ->setDescription('Génère un controller.')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom du controller (ex: UsersController)')
            ->addOption('model', 'm', InputOption::VALUE_NONE, 'Génère un modèle lié automatiquement');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $controllerName = $input->getArgument('name');
        $withModel = $input->getOption('model');

        $controllerClass = pathinfo($controllerName, PATHINFO_FILENAME);
        $modelNamePlural = rtrim($controllerClass, 'Controller');       // ex: Users
        $modelClass = rtrim($modelNamePlural, 's');                     // ex: User
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelNamePlural)); // users

        // Choix du stub
        $stubFile = $withModel ? 'ControllerWithModel.stub' : 'Controller.stub';
        $stubPath = __DIR__ . '/../../stubs/' . $stubFile;

        if (!file_exists($stubPath)) {
            $output->writeln("<error>Stub manquant : $stubFile</error>");
            return Command::FAILURE;
        }

        $stubContent = file_get_contents($stubPath);
        $stubContent = str_replace(
            ['{{ className }}', '{{ modelName }}'],
            [$controllerClass, $modelClass],
            $stubContent
        );

        $controllerPath = "app/Controllers/{$controllerClass}.php";

        if (!file_exists($controllerPath)) {
            file_put_contents($controllerPath, $stubContent);
            $output->writeln("<info>✅ Controller créé : $controllerPath</info>");
        } else {
            $output->writeln("<comment>⚠️ Fichier déjà existant : $controllerPath</comment>");
        }

        // Crée le modèle si -m
        if ($withModel) {
            $modelPath = "app/Models/{$modelClass}.php";
            $modelStub = __DIR__ . '/../../stubs/Model.stub';

            if (!file_exists($modelPath)) {
                $modelContent = file_get_contents($modelStub);
                $modelContent = str_replace(
                    ['{{ className }}', '{{ tableName }}'],
                    [$modelClass, $tableName],
                    $modelContent
                );

                file_put_contents($modelPath, $modelContent);
                $output->writeln("<info>✅ Modèle lié créé : $modelPath</info>");
            } else {
                $output->writeln("<comment>⚠️ Modèle déjà existant : $modelPath</comment>");
            }
        }

        return Command::SUCCESS;
    }
}

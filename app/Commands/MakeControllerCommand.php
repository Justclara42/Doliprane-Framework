<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:controller',
    description: 'Génère un controller.'
)]
class MakeControllerCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom du controller (ex: UsersController)')
            ->addOption('model', 'm', InputOption::VALUE_NONE, 'Génère un modèle lié automatiquement');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $controllerName = $input->getArgument('name');
        $withModel = $input->getOption('model');

        $controllerClass = pathinfo($controllerName, PATHINFO_FILENAME);
        $modelNamePlural = rtrim($controllerClass, 'Controller');       // ex : Users
        $modelClass = rtrim($modelNamePlural, 's');                     // ex : User
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelNamePlural)); // users

        // Vérification ou création des répertoires
        $stubPath = ROOT . '/stubs/' . ($withModel ? 'ControllerWithModel.stub' : 'Controller.stub');
        $controllerPath = ROOT . "/app/Controllers/{$controllerClass}.php";
        $this->ensureDirectory(dirname($controllerPath), $io, true);

        if (!file_exists($stubPath)) {
            $io->error("Stub introuvable : $stubPath");
            return BaseCommand::FAILURE;
        }

        $controllerStub = file_get_contents($stubPath);
        $controllerContent = str_replace(
            ['{{ className }}', '{{ modelName }}'],
            [$controllerClass, $modelClass],
            $controllerStub
        );

        if (!file_exists($controllerPath)) {
            file_put_contents($controllerPath, $controllerContent);
            $io->success("Controller créé : $controllerPath");
        } else {
            $io->warning("Controller déjà existant : $controllerPath");
        }

        // Crée le modèle si l'option --model est passée
        if ($withModel) {
            $modelPath = ROOT . "/app/Models/{$modelClass}.php";
            $modelStubPath = ROOT . '/stubs/Model.stub';

            $this->ensureDirectory(dirname($modelPath), $io, true);

            if (!file_exists($modelStubPath)) {
                $io->error("Stub du modèle introuvable.");
                return BaseCommand::FAILURE;
            }

            if (!file_exists($modelPath)) {
                $modelStub = file_get_contents($modelStubPath);
                $modelContent = str_replace(
                    ['{{ className }}', '{{ tableName }}'],
                    [$modelClass, $tableName],
                    $modelStub
                );

                file_put_contents($modelPath, $modelContent);
                $io->success("Modèle lié créé : $modelPath");
            } else {
                $io->warning("Modèle déjà existant : $modelPath");
            }
        }

        return BaseCommand::SUCCESS;
    }
}
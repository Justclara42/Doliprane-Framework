<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:component',
    description: 'Crée un composant PHP dans app/Components.'
)]
class MakeComponentCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom du composant PHP (ex: AlertComponent)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupérer le nom du composant
        $componentName = $input->getArgument('name');

        // Générer le chemin du fichier à créer
        $path = ROOT . "/app/Components/{$componentName}.php";

        // Vérifier ou créer le répertoire
        $this->ensureDirectory(dirname($path), $io, true);

        // Vérification si le composant existe déjà
        if (file_exists($path)) {
            $io->warning("Composant déjà existant : $path");
            return self::SUCCESS;
        }

        // Charger le stub pour le modèle de classe de composant
        $stubPath = ROOT . '/stubs/PhpComponent.stub';
        if (!file_exists($stubPath)) {
            $io->error('Stub introuvable pour le composant PHP.');
            return self::FAILURE;
        }

        // Remplacement des placeholders dans le stub
        $stub = file_get_contents($stubPath);
        $content = str_replace('{{ componentName }}', $componentName, $stub);

        // Écriture du contenu dans le nouveau fichier de composant
        file_put_contents($path, $content);
        $io->success("Composant PHP créé : $path");

        return self::SUCCESS;
    }
}
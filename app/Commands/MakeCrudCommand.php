<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:crud',
    description: 'Génère le squelette complet d\'un CRUD (Controller, Model, Vue).'
)]
class MakeCrudCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom de la ressource (ex: Post)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $resourceName = ucfirst($input->getArgument('name'));

        // Génération des composants
        $io->section("Génération du CRUD pour : $resourceName");

        $this->generateComponent("make:model", $resourceName, $io);
        $this->generateComponent("make:controller", $resourceName, $io);
        $this->generateComponent("make:view", strtolower($resourceName) . '/index', $io);

        $io->success("Le CRUD complet pour $resourceName a été généré avec succès !");
        return BaseCommand::SUCCESS;
    }

    private function generateComponent(string $command, string $name, SymfonyStyle $io): void
    {
        $io->text("Exécution de la commande : $command pour $name...");
        // Attention : cette méthode utilise des sous-commandes en interne.
        $application = $this->getApplication();
        if (!$application) {
            $io->error("Impossible d'obtenir l'application Symfony Console.");
            return;
        }

        $input = new \Symfony\Component\Console\Input\ArrayInput([
            'command' => $command,
            'name'    => $name,
        ]);

        $result = $application->doRun($input, $io);
        if ($result !== BaseCommand::SUCCESS) {
            $io->warning("Une erreur s'est produite lors de l'exécution de $command pour $name.");
        }
    }
}
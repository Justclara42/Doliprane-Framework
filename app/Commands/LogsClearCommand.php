<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'logs:clear',
    description: 'Supprime ou vide les fichiers de logs.'
)]
class LogsClearCommand extends BaseCommand
{
    private string $logPath = ROOT . '/storage/logs/';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vérification de l'existence du dossier de logs
        $this->ensureDirectory($this->logPath, $io);

        // Récupérer les fichiers de logs
        $logFiles = $this->listFiles($this->logPath);
        if (empty($logFiles)) {
            $io->warning('Aucun fichier de logs trouvé.');
            return BaseCommand::FAILURE;
        }

        // Ajouter une option pour supprimer tous les logs
        array_unshift($logFiles, 'all');

        // Question pour sélectionner un fichier ou tout vider
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Quel(s) fichier(s) souhaitez-vous supprimer ? (ou `all` pour tout supprimer)',
            $logFiles,
            '0' // Par défaut "all"
        );
        $choice = $helper->ask($input, $output, $question);

        // Traiter le choix de l'utilisateur
        if ($choice === 'all') {
            $this->deleteFiles(array_map(fn($file) => $this->logPath . $file, array_slice($logFiles, 1)), $io);
            $io->success('Tous les logs ont été supprimés.');
        } else {
            unlink($this->logPath . $choice);
            $io->success("Le fichier de log {$choice} a été supprimé.");
        }

        return BaseCommand::SUCCESS;
    }
}
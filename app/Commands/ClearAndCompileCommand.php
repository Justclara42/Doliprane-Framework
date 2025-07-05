<?php

namespace App\Commands;

use App\Core\View\TemplateEngine;
use App\Core\AssetManager;
use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clear-and-compile',
    description: 'Nettoie le cache, recompile les vues et TailwindCSS'
)]
class ClearAndCompileCommand extends BaseCommand
{
    private string $cachePath = ROOT . '/storage/cache/views/';
    private string $tailwindInput = ROOT . '/resources/css/input.css';
    private string $tailwindOutput = ROOT . '/public/assets/css/tailwind.css';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vérifier l'existence du répertoire de cache
        $this->ensureDirectory($this->cachePath, $io);

        // 🧹 Étape 1 : Nettoyage du cache
        $io->section('Nettoyage du cache');
        $files = $this->listFiles($this->cachePath);
        if (empty($files)) {
            $io->warning("Aucun fichier trouvé dans le cache.");
        } else {
            $this->deleteFiles(array_map(fn($file) => $this->cachePath . $file, $files), $io);
            $io->success('Les fichiers de cache ont été supprimés avec succès.');
        }

        // ⚙️ Étape 2 : Compilation des vues
        $io->section('Compilation des vues');
        if (!$this->compileViews($io)) {
            $io->error('Échec de la compilation des vues.');
            return self::FAILURE;
        }

        // ⚙️ Étape 3 : Compilation de TailwindCSS
        $io->section('Compilation de TailwindCSS');
        if (!$this->compileTailwind($io)) {
            $io->error('Échec de la compilation de TailwindCSS.');
            return self::FAILURE;
        }

        $io->success('Toutes les tâches ont été exécutées avec succès.');
        return self::SUCCESS;
    }

    // Compilation des vues
    private function compileViews(SymfonyStyle $io): bool
    {
        try {
            $engine = new TemplateEngine();
            $assetManager = new AssetManager();
            $engine->setGlobal('assetManager', $assetManager);

            $engine->compileAllTemplates(forceCompile: true);
            $io->success('Toutes les vues ont été compilées avec succès.');
            return true;
        } catch (\Exception $exception) {
            $io->error("Erreur lors de la compilation des vues : " . $exception->getMessage());
            return false;
        }
    }

    // Compilation de TailwindCSS
    private function compileTailwind(SymfonyStyle $io): bool
    {
        try {
            $assetManager = new AssetManager();
            $io->text('Vérification et compilation de TailwindCSS...');
            $assetManager->compileTailwindIfNeeded();
            $io->success('Compilation de TailwindCSS terminée avec succès.');
            return true;
        } catch (\Exception $exception) {
            $io->error("Erreur lors de la compilation de TailwindCSS : " . $exception->getMessage());
            return false;
        }
    }
}
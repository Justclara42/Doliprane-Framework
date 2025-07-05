<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'lang:scan',
    description: 'Scanne les templates pour les balises {% ... %} utilisées pour les traductions.'
)]
class LangScanCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $directory = ROOT . '/templates';
        $matches = [];

        // Vérifie si le répertoire existe
        $this->ensureDirectory($directory, $io);

        $files = $this->getPhpFiles($directory);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match_all('/\\{\\%\\s*(.*?)\\s*\\%\\}/', $content, $found)) {
                $matches = array_merge($matches, $found[1]);
            }
        }

        $unique = array_unique($matches);
        sort($unique);

        if (empty($unique)) {
            $io->warning("Aucune balise de traduction trouvée dans les templates.");
        } else {
            $io->success("Clés de traduction détectées :");
            foreach ($unique as $str) {
                $io->listing([$str]);
            }
        }

        return BaseCommand::SUCCESS;
    }

    /**
     * Retourne tous les fichiers PHP d'un répertoire.
     *
     * @param string $directory
     * @return array
     */
    private function getPhpFiles(string $directory): array
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        return array_filter(iterator_to_array($files), function ($file) {
            return $file->isFile() && $file->getExtension() === 'php';
        });
    }
}
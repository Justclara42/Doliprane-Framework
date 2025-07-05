<?php

namespace App\Commands\Base;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class BaseCommand extends Command
{
    // Vérifie si un répertoire existe
    protected function ensureDirectory(string $path, SymfonyStyle $io, bool $create = false): void
    {
        if (!is_dir($path)) {
            if ($create) {
                mkdir($path, 0755, true);
                $io->note("Création du répertoire : {$path}");
            } else {
                throw new \RuntimeException("Le répertoire {$path} n'existe pas.");
            }
        }
    }

    // Supprime un fichier ou une liste de fichiers
    protected function deleteFiles(array $files, SymfonyStyle $io): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
                $io->text("Supprimé : {$file}");
            }
        }
    }

    // Retourne une liste de fichiers dans un répertoire donné
    protected function listFiles(string $path): array
    {
        if (!is_dir($path)) {
            return [];
        }

        return array_filter(scandir($path), fn($file) => is_file($path . DIRECTORY_SEPARATOR . $file));
    }
}
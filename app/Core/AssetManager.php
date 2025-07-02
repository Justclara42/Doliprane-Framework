<?php

namespace App\Core;

class AssetManager
{
    private array $css = [];
    private array $js = [];

    private string $cssPath = '/assets/css/';
    private string $jsPath = '/assets/js/';
    private string $tailwindInput = '/resources/css/input.css';
    private string $tailwindOutput = '/public/assets/css/tailwind.css';
    private string $publicRoot;

    public function __construct()
    {
        $this->publicRoot = $_SERVER['DOCUMENT_ROOT'];
        $this->compileTailwindIfNeeded();
    }

    // CSS
    public function addCss(string $file): void
    {
        $this->css[] = $this->cssPath . $file;
    }

    public function getCssTags(): string
    {
        $tags = '';
        foreach ($this->css as $file) {
            $tags .= "<link rel='stylesheet' href='" . $this->versionedUrl($file) . "'>\n";
        }
        return $tags;
    }

    // JS
    public function addJs(string $file): void
    {
        $this->js[] = $this->jsPath . $file;
    }

    public function getJsTags(): string
    {
        $tags = '';
        foreach ($this->js as $file) {
            $tags .= "<script src='" . $this->versionedUrl($file) . "'></script>\n";
        }
        return $tags;
    }

    // Versioning (ajout de ?v=md5 pour le cache bust)
    private function versionedUrl(string $file): string
    {
        $fullPath = $this->publicRoot . $file;
        if (file_exists($fullPath)) {
            $version = md5_file($fullPath);
            return $file . '?v=' . $version;
        }
        return $file;
    }

    // Compile Tailwind si nécessaire
    private function compileTailwindIfNeeded(): void
    {
        $input = ROOT . $this->tailwindInput;
        $output = ROOT . $this->tailwindOutput;

        if (!file_exists($output) || filemtime($input) > filemtime($output)) {
            echo "[Tailwind] Compilation en cours...\n";

            $cmd = "npx tailwindcss -i \"$input\" -o \"$output\" --minify";
            exec($cmd, $outputLog, $status);

            if ($status === 0) {
                echo "[Tailwind] ✅ Compilation réussie\n";
            } else {
                echo "[Tailwind] ❌ Erreur de compilation :\n" . implode("\n", $outputLog);
            }
        }
    }
}

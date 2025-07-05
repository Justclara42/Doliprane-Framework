<?php
namespace App\Controllers;

class LangController
{
    private array $supportedLocales;

    public function __construct()
    {
        $this->loadSupportedLocales();
    }

    /**
     * Charge toutes les langues disponibles en scannant les fichiers JSON dans le dossier 'resources/lang'.
     */
    private function loadSupportedLocales(): void
    {
        $langPath = __DIR__ . '/../../resources/lang'; // Modifier le chemin si nécessaire
        $this->supportedLocales = [];

        if (is_dir($langPath)) {
            $files = scandir($langPath);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                    $locale = pathinfo($file, PATHINFO_FILENAME);
                    $this->supportedLocales[] = $locale;
                }
            }
        }
    }
    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    public function switch()
    {
        if (isset($_POST['lang']) && in_array($_POST['lang'], $this->supportedLocales)) {
            $_SESSION['lang'] = $_POST['lang'];
        }

        // Redirection vers la page précédente
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $ref");
        exit;
    }
}
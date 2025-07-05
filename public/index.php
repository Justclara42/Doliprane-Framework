<?php

use App\Core\App;
use App\Core\View;
use App\Debug\DebugManager; // 👉 Ajoutez ceci pour inclure DebugManager
use Dotenv\Dotenv;
use Symfony\Component\Console\Application;

require_once '../vendor/autoload.php';

// Charger le fichier helpers.php où la fonction is_dev() est définie
require_once '../bootstrap/helpers.php'; // 👈 S'assurer de charger is_dev()

define('ROOT', realpath(__DIR__ . '/..'));

// Charger les variables d'environnement (.env)
$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();

// Vérifier si on est en mode CLI ou HTTP
if (PHP_SAPI !== 'cli') { // Mode HTTP
    try {
        // Démarrer une session pour l'application
        session_start();

        // Initialiser le moteur de templates
        View::init();

        // Injecter la debugbar pour APP_ENV=dev seulement
        if (is_dev()) {
            $debugManager = new DebugManager(); // 💡 Utilise la classe avec son espace de noms
            $debugManager->collectAll();
            $debugData = $debugManager->getCollectedData();

            foreach ($debugData as $key => $value) {
                View::setGlobal($key, $value);
            }

            // Générer la debugbar HTML et la partager
            ob_start();
            extract($debugData); // Extrait les données collectées pour être accessibles dans le template
            include ROOT . '/templates/components/debugbar.php';
            $debugbarHtml = ob_get_clean();

            View::setGlobal('debugbar', $debugbarHtml);
        }

        // Lancer l'application HTTP
        $app = new App();
        $response = $app->run();

        // Afficher la réponse HTTP au client
        echo $response;
    } catch (\Throwable $e) {
        // Gérer les erreurs globales et les afficher
        http_response_code(500);
        if (is_dev()) {
            echo "<pre>Erreur : " . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
        } else {
            echo "Une erreur est survenue. Veuillez contacter un administrateur.";
        }
    }
} else { // Mode CLI
    try {
        // Application CLI via Symfony Console ou autre gestionnaire de commandes
        $cliApp = new Application('Doliprane CLI Application');
        $cliApp->run();
    } catch (\Throwable $e) {
        // Gérer les erreurs globales en CLI
        fwrite(STDERR, "Erreur CLI : " . $e->getMessage() . PHP_EOL);
        exit(1);
    }
}
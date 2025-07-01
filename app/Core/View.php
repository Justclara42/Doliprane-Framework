<?php
namespace App\Core;

use App\Core\View\TemplateEngine;

class View
{
    private static TemplateEngine $engine;

    /**
     * Initialise le moteur de template + charge la langue
     */
    public static function init(): void
    {
        self::$engine = new TemplateEngine();
        self::$engine->compileAllTemplates(); // 🔁 Compile toutes les vues .dtf automatiquement

        Lang::setLocale($_SESSION['lang'] ?? 'fr_FR');
    }

    /**
     * Rend une vue .dtf
     */
    public static function render(string $view, array $data = []): void
    {
        echo self::$engine->render($view, $data);
    }

    /**
     * Définir une variable globale injectée dans toutes les vues
     */
    public static function setGlobal(string $key, mixed $value): void
    {
        self::$engine->setGlobal($key, $value);
    }

    /**
     * Obsolète depuis l'usage de @extends dans les fichiers .dtf
     */
    public static function layout(string $layout, string $template, array $data = []): void
    {
        throw new \Exception("View::layout() est obsolète avec les templates .dtf. Utilise View::render('template', [...])");
    }
}

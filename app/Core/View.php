<?php

namespace App\Core;

use App\Core\View\TemplateEngine;

class View
{
    private static ?TemplateEngine $engine = null;

    /**
     * Variables partagées globalement avec toutes les vues.
     */
    protected static array $shared = [];

    public static function init(): void
    {
        $assetManager = new AssetManager();
        $assetManager->addCss('tailwind.css');
        $assetManager->addJs('app.js');

        self::$engine = new TemplateEngine();
        self::$engine->setGlobal('assetManager', $assetManager);
        self::$engine->compileAllTemplates();

        Lang::setLocale($_SESSION['lang'] ?? 'fr_FR');
    }

    public static function render(string $view, array $data = []): void
    {
        if (!self::$engine) {
            throw new \RuntimeException("Le moteur de templates n'a pas été initialisé.");
        }

        // Fusionne les données avec les variables partagées
        $mergedData = array_merge(self::$shared, $data);

        $output = self::$engine->render($view, $mergedData);
        echo $output;
    }

    public static function setGlobal(string $key, mixed $value): void
    {
        if (!self::$engine) {
            throw new \RuntimeException("Le moteur de templates n'a pas été initialisé.");
        }

        self::$engine->setGlobal($key, $value);
    }

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function layout(string $layout, string $template, array $data = []): void
    {
        throw new \Exception("View::layout() est obsolète avec les templates .dtf. Utilise View::render('template', [...])");
    }
}

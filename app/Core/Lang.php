<?php

namespace App\Core;

class Lang
{
    private static array $translations = [];
    private static string $locale = 'fr_FR';
    private static bool $loaded = false;

    /**
     * Définit la langue active et charge le fichier de traduction associé
     */
    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
        self::$loaded = false; // Ajoute ceci pour forcer le rechargement

        self::loadTranslations();
    }

    /**
     * Charge les traductions si ce n'est pas déjà fait
     */
    private static function loadTranslations(): void
    {
        if (self::$loaded) return;

        $file = dirname(__DIR__, 2) . '/resources/lang/' . self::$locale . '.json';

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);
            self::$translations = is_array($data) ? $data : [];
        } else {
            self::$translations = [];
        }

        self::$loaded = true;
    }

    /**
     * Récupère la traduction d'une clé
     */
    public static function get(string $key): string
    {
        self::loadTranslations(); // S'assure que c’est chargé même si setLocale jamais appelé
        return self::$translations[$key] ?? $key;
    }

    /**
     * Récupère la locale actuelle
     */
    public static function getLocale(): string
    {
        return self::$locale;
    }
}

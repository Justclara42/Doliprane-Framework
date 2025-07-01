<?php

namespace App\Core;

class Lang
{
    private static array $translations = [];
    private static string $locale = 'fr_FR';

    /**
     * Définit la langue active et charge le fichier de traduction associé
     */
    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
        $file = dirname(__DIR__, 2) . '/resources/lang/' . $locale . '.json';
        file_put_contents('lang_debug.log', "Chargement de $locale -> $file\n", FILE_APPEND);

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);
            self::$translations = is_array($data) ? $data : [];
        } else {
            self::$translations = [];
        }
    }

    /**
     * Récupère la traduction d'une clé, ou retourne la clé elle-même si absente
     */
    public static function get(string $key): string
    {
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


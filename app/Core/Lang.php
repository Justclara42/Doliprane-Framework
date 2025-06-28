<?php
namespace App\Core;

class Lang
{
    private static array $translations = [];
    private static string $locale = 'fr_FR';

    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
        $file = __DIR__ . '/../../lang/' . $locale . '.json';

        if (file_exists($file)) {
            self::$translations = json_decode(file_get_contents($file), true);
        } else {
            self::$translations = [];
        }
    }

    public static function translate(string $content): string
    {
        return preg_replace_callback('/\{\%\s*(.*?)\s*\%\}/', function ($matches) {
            $key = $matches[1];
            return self::$translations[$key] ?? "{% $key %}";
        }, $content);
    }
}

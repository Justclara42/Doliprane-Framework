<?php
namespace App\Core;

class View
{
    private static function translate(string $content): string
    {
        $lang = $_SESSION['lang'] ?? 'fr_FR';
        $file = ROOT . "/resources/lang/{$lang}.json";

        $tr = [];
        if (file_exists($file)) {
            $tr = json_decode(file_get_contents($file), true);
        }

        return preg_replace_callback('/\{\%\s*(.+?)\s*\%\}/', function ($m) use ($tr) {
            return $tr[$m[1]] ?? "{% {$m[1]} %}";
        }, $content);
    }

    public static function render(string $view, array $data = []): void
    {
        ob_start();
        extract($data);
        require ROOT . "/templates/{$view}.php";
        $html = ob_get_clean();
        echo self::translate($html);
    }

    public static function layout(string $layout, string $template, array $data = []): void
    {
        $templateFile = ROOT . "/templates/{$template}.php";

        ob_start();
        extract($data);
        require $templateFile;
        $content = ob_get_clean();

        $translatedContent = self::translate($content);

        // injecte la variable traduite pour base.php
        $GLOBALS['__view_translated_content'] = $translatedContent;

        require ROOT . "/templates/layouts/{$layout}.php";
    }
}

<?php

namespace App\Core\View;

use App\Debug\ErrorLogger;

class TemplateEngine
{
    protected string $templatePath;
    protected string $cachePath;
    protected array $globals = [];
    protected array $sections = [];
    protected array $stacks = [];
    protected array $filters = [];

    public function __construct()
    {
        $this->templatePath = ROOT . '/templates/';
        $this->cachePath = ROOT . '/storage/cache/views/';
        require_once ROOT . '/app/Core/Lang.php';
    }

    public function render(string $view, array $data = [], bool $forceCompile = false): string
    {
        $templateFile = $this->getTemplateFilePath($view);
        $compiledFile = $this->getCompiledFilePath($view);

        if (!file_exists($templateFile)) {
            throw new \Exception("Fichier template introuvable : $templateFile");
        }

        // Modifié : permet de forcer la compilation si nécessaire
        if (($forceCompile || is_dev()) && (!file_exists($compiledFile) || filemtime($compiledFile) < filemtime($templateFile))) {
            $templateContent = file_get_contents($templateFile);
            $compiledContent = $this->compile($templateContent, $view);

            if (!is_dir(dirname($compiledFile)) && !mkdir(dirname($compiledFile), 0775, true) && !is_dir(dirname($compiledFile))) {
                throw new \RuntimeException("Impossible de créer le dossier de cache : " . dirname($compiledFile));
            }

            file_put_contents($compiledFile, $compiledContent);
        }

        extract(array_merge($this->globals, $data));

        foreach (['code', 'message', 'trace', 'debug', 'devMode'] as $key) {
            if (!isset($$key)) {
                $$key = null;
            }
        }

        ob_start();
        require_once ROOT . '/bootstrap/helpers.php';

        include $compiledFile;
        return ob_get_clean();
    }

    public function setGlobal(string $key, mixed $value): void
    {
        $this->globals[$key] = $value;
    }

    public function registerFilter(string $name, callable $callback): void
    {
        $this->filters[$name] = $callback;
    }

    protected function compile(string $template, string $currentView): string
    {
        // Supprimer les commentaires
        $template = preg_replace('/\{#.*?#\}/s', '', $template);

        // Includes
        $template = preg_replace_callback('/\{% include ["\'](.+?)["\'] %\}/', function ($matches) {
            $includedPath = $this->getTemplateFilePath($matches[1]);
            return file_exists($includedPath) ? file_get_contents($includedPath) : "<!-- Include not found: {$matches[1]} -->";
        }, $template);

        // Traduction
        $template = preg_replace('/\{\{\s*lang\((["\'])(.+?)\1\)\s*\}\}/', '<?= lang("$2") ?>', $template);

        // Conditions
        $template = preg_replace('/\{% if (.+?) %\}/', '<?php if ($1): ?>', $template);
        $template = preg_replace('/\{% elseif (.+?) %\}/', '<?php elseif ($1): ?>', $template);
        $template = str_replace('{% else %}', '<?php else: ?>', $template);
        $template = str_replace('{% endif %}', '<?php endif; ?>', $template);

        // Boucles
        $template = preg_replace('/\{% foreach (.+?) as (.+?) %\}/', '<?php foreach ($1 as $2): ?>', $template);
        $template = str_replace('{% endforeach %}', '<?php endforeach; ?>', $template);
        $template = preg_replace('/\{% for (.+?) %\}/', '<?php for ($1): ?>', $template);
        $template = str_replace('{% endfor %}', '<?php endfor; ?>', $template);

        // Filtres personnalisés ou simples
        $template = preg_replace_callback('/\{% (.+?)\|(.+?) %\}/', function ($m) {
            return '<?= ' . $this->applyFilter(trim($m[1]), trim($m[2])) . ' ?>';
        }, $template);

        $template = preg_replace('/\{% (.+?) %\}/', '<?= $1 ?>', $template);

        $template = preg_replace_callback('/\{\{\s*(.+?)\|(.+?)\s*\}\}/', function ($m) {
            return '<?= ' . $this->applyFilter(trim($m[1]), trim($m[2])) . ' ?>';
        }, $template);

        $template = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= htmlspecialchars($1) ?>', $template);

        // Layouts
        if (preg_match('/@extends\(["\'](.+?)["\']\)/', $template, $matches)) {
            $layout = $matches[1];
            $layoutPath = $this->getTemplateFilePath($layout);
            $layoutContent = file_exists($layoutPath) ? file_get_contents($layoutPath) : '';

            $template = preg_replace('/@extends\(["\'](.+?)["\']\)/', '', $template);

            // Sections
            preg_match_all('/@section\(["\'](.+?)["\']\)(.*?)@endsection/s', $template, $sectionMatches, PREG_SET_ORDER);
            foreach ($sectionMatches as $section) {
                $this->sections[$section[1]] = $section[2];
            }
            $template = preg_replace('/@section\(["\'](.+?)["\']\)(.*?)@endsection/s', '', $template);

            // Nettoyer les @section non fermés
            //$template = preg_replace('/@section\(["\'](.+?)["\']\)(.*)$/s', '', $template);

            // Push
            preg_match_all('/@push\(["\'](.+?)["\']\)(.*?)@endpush/s', $template, $pushMatches, PREG_SET_ORDER);
            foreach ($pushMatches as $push) {
                if (!isset($this->stacks[$push[1]])) {
                    $this->stacks[$push[1]] = [];
                }
                $this->stacks[$push[1]][] = $push[2];
            }

            $layoutContent = preg_replace_callback('/@yield\(["\'](.+?)["\']\)/', function ($m) {
                return $this->sections[$m[1]] ?? '';
            }, $layoutContent);

            $layoutContent = preg_replace_callback('/@stack\(["\'](.+?)["\']\)/', function ($m) {
                return implode("\n", $this->stacks[$m[1]] ?? []);
            }, $layoutContent);

            return $this->compile($layoutContent, $layout);
        }

        return $template;
    }

    public function compileAllTemplates(bool $forceCompile = false): void
    {
        $directory = new \RecursiveDirectoryIterator($this->templatePath);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'dtf') {
                $relativePath = str_replace($this->templatePath, '', $file->getPathname());
                $view = str_replace(['/', '\\'], '.', substr($relativePath, 0, -4));

                // Supprimez ou loggez les messages d'info
                // Exemple 1 : Suppression pure
                // try {
                //    $this->render($view, [], $forceCompile);
                // } ...

                // Exemple 2 : Log dans un fichier au lieu d'afficher
                try {
                    $this->render($view, [], $forceCompile);
                    ErrorLogger::logError("INFO : Compilation de la vue : $view", __FILE__, __LINE__);
                } catch (\Exception $e) {
                    // Enregistrer l'erreur dans le fichier de log ou base de données
                    ErrorLogger::logError("❌ Erreur compilation $view : " . $e->getMessage(), __FILE__, __LINE__);
                }
            }
        }
    }

    protected function applyFilter(string $expression, ?string $filter): string
    {
        if (!str_starts_with($expression, '$')) {
            $expression = '$' . $expression;
        }

        if (isset($this->filters[$filter])) {
            return "call_user_func(\$this->filters['$filter'], $expression)";
        }

        return match ($filter) {
            'upper'    => "strtoupper($expression)",
            'lower'    => "strtolower($expression)",
            'ucfirst'  => "ucfirst($expression)",
            'escape'   => "htmlspecialchars($expression)",
            null, ''   => "htmlspecialchars($expression)",
            default    => "'<!-- Filtre inconnu : $filter -->'"
        };
    }

    protected function getTemplateFilePath(string $view): string
    {
        return $this->templatePath . str_replace('.', '/', $view) . '.dtf';
    }

    protected function getCompiledFilePath(string $view): string
    {
        return $this->cachePath . str_replace('.', '_', $view) . '.php';
    }
}
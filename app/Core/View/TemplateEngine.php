<?php

namespace App\Core\View;

class TemplateEngine
{
    protected string $templatePath;
    protected string $cachePath;
    protected array $globals = [];
    protected array $sections = [];
    protected array $stacks = [];
    protected array $pushBuffer = [];

    public function __construct()
    {
        $this->templatePath = ROOT . '/templates/';
        $this->cachePath = ROOT . '/storage/cache/views/';

        // ✅ S'assurer que la fonction lang() est toujours dispo
        require_once ROOT . '/app/Core/Lang.php';
    }

    public function render(string $view, array $data = []): string
    {
        $templateFile = $this->getTemplateFilePath($view);
        $compiledFile = $this->getCompiledFilePath($view);

        if (!file_exists($templateFile)) {
            throw new \Exception("Fichier template introuvable : $templateFile");
        }

        if (!file_exists($compiledFile) || filemtime($compiledFile) < filemtime($templateFile)) {
            $templateContent = file_get_contents($templateFile);
            $compiledContent = $this->compile($templateContent, $view);

            if (!is_dir(dirname($compiledFile))) {
                mkdir(dirname($compiledFile), 0775, true);
            }

            file_put_contents($compiledFile, $compiledContent);
        }

        extract(array_merge($this->globals, $data));

        ob_start();
        include $compiledFile;
        return ob_get_clean();
    }

    public function setGlobal(string $key, mixed $value): void
    {
        $this->globals[$key] = $value;
    }

    protected function compile(string $template, string $currentView): string
    {
        // {% include %}
        $template = preg_replace_callback('/\{% include "(.+?)" %\}/', function ($matches) {
            $includedPath = $this->getTemplateFilePath($matches[1]);
            return file_exists($includedPath)
                ? file_get_contents($includedPath)
                : "<!-- Include not found: {$matches[1]} -->";
        }, $template);

        // {{ lang("...") }}
        $template = preg_replace('/\{\{\s*lang\(["\'](.+?)["\']\)\s*\}\}/', '<?= lang("$1") ?>', $template);

        // {% variable|filter %}
        $template = preg_replace_callback('/\{% (.+?)\|(.+?) %\}/', function ($matches) {
            $expression = trim($matches[1]);
            $filter = trim($matches[2]);
            return '<?= ' . $this->applyFilter($expression, $filter) . ' ?>';
        }, $template);

        // {% variable %}
        $template = preg_replace('/\{% (.+?) %\}/', '<?= htmlspecialchars($1) ?>', $template);

        // Conditions
        $template = preg_replace('/\{% if (.+?) %\}/', '<?php if ($1): ?>', $template);
        $template = preg_replace('/\{% elseif (.+?) %\}/', '<?php elseif ($1): ?>', $template);
        $template = preg_replace('/\{% else %\}/', '<?php else: ?>', $template);
        $template = preg_replace('/\{% endif %\}/', '<?php endif; ?>', $template);

        // Boucles
        $template = preg_replace('/\{% foreach (.+?) as (.+?) %\}/', '<?php foreach ($1 as $2): ?>', $template);
        $template = preg_replace('/\{% endforeach %\}/', '<?php endforeach; ?>', $template);

        $template = preg_replace('/\{% for (.+?) %\}/', '<?php for ($1): ?>', $template);
        $template = preg_replace('/\{% endfor %\}/', '<?php endfor; ?>', $template);

        // @extends support
        if (preg_match('/@extends\([\'"](.+?)[\'"]\)/', $template, $matches)) {
            $layout = $matches[1];
            $layoutPath = $this->getTemplateFilePath($layout);
            $layoutContent = file_exists($layoutPath) ? file_get_contents($layoutPath) : '';

            // Supprime @extends
            $template = preg_replace('/@extends\([\'"].+?[\'"]\)/', '', $template);

            // @section
            preg_match_all('/@section\([\'"](.+?)[\'"]\)(.*?)@endsection/s', $template, $sectionMatches, PREG_SET_ORDER);
            foreach ($sectionMatches as $section) {
                $this->sections[$section[1]] = $section[2];
            }

            // Nettoyer les blocs @section
            $template = preg_replace('/@section\([\'"].+?[\'"]\)(.*?)@endsection/s', '', $template);

            // @yield remplacement
            $layoutContent = preg_replace_callback('/@yield\([\'"](.+?)[\'"]\)/', function ($m) {
                return $this->sections[$m[1]] ?? '';
            }, $layoutContent);

            // @push / @stack
            preg_match_all('/@push\([\'"](.+?)[\'"]\)(.*?)@endpush/s', $template, $pushMatches, PREG_SET_ORDER);
            foreach ($pushMatches as $push) {
                $this->stacks[$push[1]][] = $push[2];
            }

            $layoutContent = preg_replace_callback('/@stack\([\'"](.+?)[\'"]\)/', function ($m) {
                return implode("\n", $this->stacks[$m[1]] ?? []);
            }, $layoutContent);

            // 🔁 Compile récursivement le layout
            return $this->compile($layoutContent, $layout);
        }

        return $template;
    }


    /**
     * Compile toutes les vues .dtf présentes dans le dossier des templates
     */
    public function compileAllTemplates(): void
    {
        $directory = new \RecursiveDirectoryIterator($this->templatePath);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'dtf') {
                // Transforme le chemin complet en identifiant de vue ("docs.intro" par exemple)
                $relativePath = str_replace($this->templatePath, '', $file->getPathname());
                $view = str_replace(['/', '\\'], '.', substr($relativePath, 0, -4)); // Supprime .dtf

                try {
                    $this->render($view); // ⏩ Cela déclenche automatiquement la compilation
                    //echo "✅ Compilé : $view\n";
                } catch (\Exception $e) {
                    echo "❌ Erreur compilation $view : " . $e->getMessage() . "\n";
                }
            }
        }
    }


    protected function applyFilter(string $expression, ?string $filter): string
    {
        $expression = '$' . $expression;

        return match ($filter) {
            'upper'   => "strtoupper($expression)",
            'lower'   => "strtolower($expression)",
            'ucfirst' => "ucfirst($expression)",
            'escape'  => "htmlspecialchars($expression)",
            null, ''  => "htmlspecialchars($expression)",
            default   => "'<!-- Filtre inconnu : $filter -->'"
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

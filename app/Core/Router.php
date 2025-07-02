<?php
namespace App\Core;

use App\Controllers\ErrorController;

class Router
{
    /** @var array<int, array{0: string, 1: string, 2: string}> */
    private array $routes;

    public function __construct(string $routeFile)
    {
        $this->routes = require $routeFile;
    }

    public function dispatch(string $method, string $uri): mixed
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            [$routeMethod, $routePattern, $controllerAction] = $route;
            if ($method !== $routeMethod) continue;

            $cleanedPattern = rtrim($routePattern, '/') ?: '/';
            $pattern = preg_replace('#\{([\w]+)\}#', '(?P<\1>[^/]+)', $cleanedPattern);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->invoke($controllerAction, $params);
            }
        }

        $this->handleError(404, "Page non trouvée.");
        return null;
    }

    private function invoke(string $controllerAction, array $params): mixed
    {
        [$controllerName, $method] = explode('@', $controllerAction);
        $controllerClass = "App\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
            $this->handleError(500, "Contrôleur $controllerClass introuvable");
            return null;
        }

        $controller = new $controllerClass;
        if (!method_exists($controller, $method)) {
            $this->handleError(500, "Méthode $method absente dans $controllerClass");
            return null;
        }

        return call_user_func_array([$controller, $method], $params);
    }

    private function handleError(int $code, string $message = ''): void
    {
        (new ErrorController())->show($code, $message);
    }
}

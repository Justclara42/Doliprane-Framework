<?php
namespace App\Core;

class Router
{
    private array $routes;

    public function __construct(string $routeFile)
    {
        $this->routes = require $routeFile;
    }

    public function dispatch(string $method, string $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            [$routeMethod, $routePattern, $controllerAction] = $route;
            if ($method !== $routeMethod) continue;

            // Nettoyer aussi le routePattern ici
            $cleanedPattern = rtrim($routePattern, '/') ?: '/';

            // Convertir {param} en regex
            $pattern = preg_replace('#\{([\w]+)\}#', '(?P<\1>[^/]+)', $cleanedPattern);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->invoke($controllerAction, $params);
            }
        }

        if (!headers_sent()) {
            http_response_code(404);
        }
        echo "404 Not Found";
    }

    private function invoke(string $controllerAction, array $params)
    {
        [$controllerName, $method] = explode('@', $controllerAction);
        $controllerClass = "App\\Controllers\\" . $controllerName;

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller $controllerClass not found");
        }

        $controller = new $controllerClass;
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method $method not found in $controllerClass");
        }

        return call_user_func_array([$controller, $method], $params);
    }
}

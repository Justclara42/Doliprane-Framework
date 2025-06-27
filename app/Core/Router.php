<?php
namespace App\Core;

class Router {
    protected array $routes = [];
    public function get(string $path, $action) { $this->routes['GET'][$path] = $action; }
    public function post(string $path, $action) { $this->routes['POST'][$path] = $action; }
    public function dispatch(string $uri, string $method) {
        $path = parse_url($uri, PHP_URL_PATH);
        $action = $this->routes[$method][$path] ?? null;
        if (!$action) { http_response_code(404); echo "404"; exit; }
        [$controller, $method] = $action;
        (new $controller)->$method();
    }
}

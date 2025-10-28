<?php

class Router {
    private $routes = [];
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function addRoute($path, $template) {
        $this->routes[$path] = $template;
    }

    public function dispatch($path) {
        // Exact match first
        if (isset($this->routes[$path])) {
            echo $this->twig->render($this->routes[$path]);
            return;
        }

        // Prefix match for routes like /auth, /dashboard, etc.
        foreach ($this->routes as $routePath => $template) {
            if (strpos($path, $routePath) === 0) {
                echo $this->twig->render($template);
                return;
            }
        }

        // Default 404
        http_response_code(404);
        echo $this->twig->render('landing.twig');
    }
}